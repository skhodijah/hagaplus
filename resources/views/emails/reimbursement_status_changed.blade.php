<!DOCTYPE html>
<html>
<head>
    <title>Reimbursement Status Update</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background-color: #f8f9fa; padding: 20px; text-align: center; border-bottom: 1px solid #dee2e6; }
        .content { padding: 20px; }
        .footer { text-align: center; padding: 20px; font-size: 12px; color: #6c757d; }
        .status-badge { display: inline-block; padding: 5px 10px; border-radius: 4px; font-weight: bold; color: white; }
        .bg-green { background-color: #28a745; }
        .bg-red { background-color: #dc3545; }
        .bg-blue { background-color: #007bff; }
        .bg-yellow { background-color: #ffc107; color: #333; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>Reimbursement Update</h2>
        </div>
        <div class="content">
            <p>Hello {{ $reimbursement->employee->user->name }},</p>
            
            <p>The status of your reimbursement request <strong>#{{ $reimbursement->reference_number }}</strong> has been updated.</p>
            
            <p>
                <strong>Current Status:</strong> 
                <span class="status-badge {{ $reimbursement->status === 'rejected' ? 'bg-red' : ($reimbursement->status === 'paid' ? 'bg-green' : 'bg-blue') }}">
                    {{ ucfirst(str_replace('_', ' ', $reimbursement->status)) }}
                </span>
            </p>

            @if($reimbursement->status === 'rejected' && $reimbursement->rejection_reason)
                <div style="background-color: #fff3cd; border: 1px solid #ffeeba; padding: 10px; margin-top: 10px; border-radius: 4px;">
                    <strong>Rejection Reason:</strong><br>
                    {{ $reimbursement->rejection_reason }}
                </div>
            @endif

            <table style="width: 100%; margin-top: 20px; border-collapse: collapse;">
                <tr>
                    <td style="padding: 8px; border-bottom: 1px solid #eee;"><strong>Category:</strong></td>
                    <td style="padding: 8px; border-bottom: 1px solid #eee;">{{ $reimbursement->category }}</td>
                </tr>
                <tr>
                    <td style="padding: 8px; border-bottom: 1px solid #eee;"><strong>Amount:</strong></td>
                    <td style="padding: 8px; border-bottom: 1px solid #eee;">{{ $reimbursement->currency }} {{ number_format($reimbursement->amount, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <td style="padding: 8px; border-bottom: 1px solid #eee;"><strong>Date:</strong></td>
                    <td style="padding: 8px; border-bottom: 1px solid #eee;">{{ $reimbursement->date_of_expense->format('d M Y') }}</td>
                </tr>
            </table>

            <p style="margin-top: 20px;">
                You can view the full details of your request by logging into the employee portal.
            </p>
            
            <p>
                <a href="{{ route('employee.reimbursements.show', $reimbursement) }}" style="display: inline-block; padding: 10px 20px; background-color: #007bff; color: white; text-decoration: none; border-radius: 5px;">
                    View Request
                </a>
            </p>
        </div>
        <div class="footer">
            &copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
        </div>
    </div>
</body>
</html>
