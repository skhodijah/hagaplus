<?php

namespace App\Services;

use App\Models\Admin\ApprovalFlow;
use App\Models\Admin\ApprovalRequest;
use App\Models\Admin\ApprovalStep;
use App\Models\Admin\Employee;
use App\Models\Admin\InstansiRole;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ApprovalService
{
    /**
     * Create approval request for a model (Leave, AttendanceRevision, etc.)
     * 
     * @param Model $approvable The model that needs approval
     * @param string $flowType The type of approval flow
     * @param int $requesterId The user ID of the requester
     * @return ApprovalRequest
     */
    public function createApprovalRequest(Model $approvable, string $flowType, int $requesterId)
    {
        return DB::transaction(function () use ($approvable, $flowType, $requesterId) {
            // Get the employee record for the requester
            $employee = Employee::where('user_id', $requesterId)->first();
            
            if (!$employee) {
                throw new \Exception('Employee record not found for user');
            }

            // Get the approval flow for this type
            $flow = ApprovalFlow::where('instansi_id', $employee->instansi_id)
                ->where('flow_type', $flowType)
                ->where('is_active', true)
                ->first();

            // If no custom flow exists, create a default one
            if (!$flow) {
                $flow = $this->createDefaultApprovalFlow($employee->instansi_id, $flowType);
            }

            // Create the approval request
            $approvalRequest = ApprovalRequest::create([
                'approval_flow_id' => $flow->id,
                'approvable_type' => get_class($approvable),
                'approvable_id' => $approvable->id,
                'requester_id' => $requesterId,
                'employee_id' => $employee->id,
                'status' => 'pending',
                'current_level' => 1,
                'submitted_at' => now(),
            ]);

            // Create approval steps based on employee hierarchy
            $this->createApprovalSteps($approvalRequest, $employee, $flowType);

            return $approvalRequest;
        });
    }

    /**
     * Create approval steps based on employee hierarchy
     */
    protected function createApprovalSteps(ApprovalRequest $approvalRequest, Employee $employee, string $flowType)
    {
        $stepOrder = 1;
        $flow = $approvalRequest->flow;

        // Get approval hierarchy from employee
        $hierarchy = $employee->getApprovalHierarchy();

        foreach ($hierarchy as $level) {
            // Find corresponding approval level in flow
            $approvalLevel = $flow->levels()
                ->where('approver_type', $level['type'])
                ->first();

            if ($approvalLevel) {
                ApprovalStep::create([
                    'approval_request_id' => $approvalRequest->id,
                    'approval_level_id' => $approvalLevel->id,
                    'step_order' => $stepOrder,
                    'approver_id' => $level['user_id'],
                    'status' => $stepOrder === 1 ? 'pending' : 'pending',
                ]);

                $stepOrder++;
            }
        }

        // For reimbursement and expense, add Finance approval as final step
        if (in_array($flowType, ['reimbursement', 'expense'])) {
            $financeLevel = $flow->levels()
                ->where('approver_type', 'finance')
                ->first();

            if ($financeLevel) {
                // Get Finance role users
                $financeRole = InstansiRole::where('instansi_id', $employee->instansi_id)
                    ->whereHas('systemRole', function ($q) {
                        $q->where('name', 'Finance')->orWhere('slug', 'finance');
                    })
                    ->first();

                if ($financeRole) {
                    $financeUser = Employee::where('instansi_role_id', $financeRole->id)
                        ->where('instansi_id', $employee->instansi_id)
                        ->first();

                    if ($financeUser) {
                        ApprovalStep::create([
                            'approval_request_id' => $approvalRequest->id,
                            'approval_level_id' => $financeLevel->id,
                            'step_order' => $stepOrder,
                            'approver_id' => $financeUser->user_id,
                            'status' => 'pending',
                        ]);
                    }
                }
            }
        }
    }

    /**
     * Approve an approval step
     */
    public function approveStep(ApprovalStep $step, int $approverId, string $notes = null)
    {
        return DB::transaction(function () use ($step, $approverId, $notes) {
            // Verify the approver
            if ($step->approver_id !== $approverId) {
                throw new \Exception('You are not authorized to approve this step');
            }

            // Check if step is already processed
            if ($step->status !== 'pending') {
                throw new \Exception('This step has already been processed');
            }

            // Update the step
            $step->update([
                'status' => 'approved',
                'notes' => $notes,
                'approved_at' => now(),
            ]);

            $request = $step->request;

            // Check if there are more steps
            $nextStep = $request->steps()
                ->where('step_order', '>', $step->step_order)
                ->where('status', 'pending')
                ->orderBy('step_order')
                ->first();

            if ($nextStep) {
                // Move to next level
                $request->update([
                    'status' => 'in_progress',
                    'current_level' => $nextStep->step_order,
                ]);
            } else {
                // All steps approved - complete the request
                $request->update([
                    'status' => 'approved',
                    'completed_at' => now(),
                ]);

                // Update the approvable model
                $this->completeApproval($request->approvable);
            }

            return $request;
        });
    }

    /**
     * Reject an approval step
     */
    public function rejectStep(ApprovalStep $step, int $approverId, string $reason)
    {
        return DB::transaction(function () use ($step, $approverId, $reason) {
            // Verify the approver
            if ($step->approver_id !== $approverId) {
                throw new \Exception('You are not authorized to reject this step');
            }

            // Check if step is already processed
            if ($step->status !== 'pending') {
                throw new \Exception('This step has already been processed');
            }

            // Update the step
            $step->update([
                'status' => 'rejected',
                'notes' => $reason,
                'approved_at' => now(),
            ]);

            // Update the request
            $request = $step->request;
            $request->update([
                'status' => 'rejected',
                'rejection_reason' => $reason,
                'completed_at' => now(),
            ]);

            // Update the approvable model
            $this->rejectApproval($request->approvable, $reason);

            return $request;
        });
    }

    /**
     * Complete approval on the approvable model
     */
    protected function completeApproval(Model $approvable)
    {
        if (method_exists($approvable, 'onApprovalComplete')) {
            $approvable->onApprovalComplete();
        } else {
            // Default behavior
            $approvable->update([
                'status' => 'approved',
                'approved_at' => now(),
            ]);
        }
    }

    /**
     * Reject the approvable model
     */
    protected function rejectApproval(Model $approvable, string $reason)
    {
        if (method_exists($approvable, 'onApprovalRejected')) {
            $approvable->onApprovalRejected($reason);
        } else {
            // Default behavior
            $approvable->update([
                'status' => 'rejected',
                'rejection_reason' => $reason,
            ]);
        }
    }

    /**
     * Create a default approval flow if none exists
     */
    protected function createDefaultApprovalFlow(int $instansiId, string $flowType): ApprovalFlow
    {
        $flowNames = [
            'leave' => 'Leave Approval',
            'permission' => 'Permission Approval',
            'sick' => 'Sick Leave Approval',
            'attendance_revision' => 'Attendance Revision Approval',
            'reimbursement' => 'Reimbursement Approval',
            'expense' => 'Expense Approval',
        ];

        $flow = ApprovalFlow::create([
            'instansi_id' => $instansiId,
            'name' => $flowNames[$flowType] ?? 'Approval Flow',
            'flow_type' => $flowType,
            'description' => 'Default approval flow',
            'is_active' => true,
        ]);

        // Create default levels
        $levels = [
            ['level_order' => 1, 'level_name' => 'Supervisor', 'approver_type' => 'supervisor'],
            ['level_order' => 2, 'level_name' => 'Manager', 'approver_type' => 'manager'],
        ];

        // Add Finance level for reimbursement/expense
        if (in_array($flowType, ['reimbursement', 'expense'])) {
            $levels[] = ['level_order' => 3, 'level_name' => 'Finance', 'approver_type' => 'finance'];
        }

        foreach ($levels as $levelData) {
            $flow->levels()->create(array_merge($levelData, [
                'is_required' => true,
            ]));
        }

        return $flow;
    }

    /**
     * Get pending approvals for a user
     */
    public function getPendingApprovalsForUser(int $userId)
    {
        return ApprovalStep::where('approver_id', $userId)
            ->where('status', 'pending')
            ->with(['request.approvable', 'request.requester', 'level'])
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Get approval history for an approvable model
     */
    public function getApprovalHistory(Model $approvable)
    {
        $request = ApprovalRequest::where('approvable_type', get_class($approvable))
            ->where('approvable_id', $approvable->id)
            ->with(['steps.approver', 'steps.level'])
            ->first();

        return $request;
    }
}
