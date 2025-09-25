<x-superadmin-layout>
    <div class="py-2">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <x-page-header
                title="Support Request Detail"
                subtitle="Tindak lanjuti permintaan dukungan"
                :show-period-filter="false"
            />

            @if(session('success'))
                <div class="mb-4 bg-green-100 dark:bg-green-900 border border-green-400 dark:border-green-600 text-green-700 dark:text-green-300 px-4 py-3 rounded">
                    {{ session('success') }}
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <div class="lg:col-span-2">
                    <x-section-card title="Informasi Permintaan">
                        <dl class="grid grid-cols-1 gap-4">
                            <div>
                                <dt class="text-sm text-gray-500 dark:text-gray-400">Instansi</dt>
                                <dd class="text-sm font-medium text-gray-900 dark:text-white">{{ $requestItem->instansi->nama_instansi ?? '-' }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm text-gray-500 dark:text-gray-400">Requester</dt>
                                <dd class="text-sm text-gray-900 dark:text-white">{{ $requestItem->requester->email ?? '-' }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm text-gray-500 dark:text-gray-400">Subject</dt>
                                <dd class="text-sm text-gray-900 dark:text-white">{{ $requestItem->subject }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm text-gray-500 dark:text-gray-400">Message</dt>
                                <dd class="text-sm text-gray-900 dark:text-white whitespace-pre-wrap">{{ $requestItem->message }}</dd>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <dt class="text-sm text-gray-500 dark:text-gray-400">Dibuat</dt>
                                    <dd class="text-sm text-gray-900 dark:text-white">{{ $requestItem->created_at->format('d M Y H:i') }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm text-gray-500 dark:text-gray-400">Terakhir Update</dt>
                                    <dd class="text-sm text-gray-900 dark:text-white">{{ $requestItem->updated_at->format('d M Y H:i') }}</dd>
                                </div>
                            </div>
                        </dl>
                    </x-section-card>
                </div>
                <div>
                    <x-section-card title="Update Status">
                        <form method="POST" action="{{ route('superadmin.support_requests.update', $requestItem->id) }}">
                            @csrf
                            @method('PATCH')
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm mb-1">Status</label>
                                    <select name="status" class="w-full px-3 py-2 border rounded-md dark:bg-gray-700 dark:text-white">
                                        @foreach(['open','in_progress','resolved','closed'] as $s)
                                            <option value="{{ $s }}" {{ $requestItem->status === $s ? 'selected' : '' }}>{{ ucwords(str_replace('_',' ',$s)) }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm mb-1">Priority</label>
                                    <select name="priority" class="w-full px-3 py-2 border rounded-md dark:bg-gray-700 dark:text-white">
                                        @foreach(['low','normal','high','urgent'] as $p)
                                            <option value="{{ $p }}" {{ $requestItem->priority === $p ? 'selected' : '' }}>{{ ucfirst($p) }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm mb-1">Catatan Admin</label>
                                    <textarea name="admin_notes" rows="4" class="w-full px-3 py-2 border rounded-md dark:bg-gray-700 dark:text-white">{{ old('admin_notes', $requestItem->admin_notes) }}</textarea>
                                </div>
                            </div>
                            <div class="mt-4 flex justify-end">
                                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md">Simpan</button>
                            </div>
                        </form>
                    </x-section-card>
                </div>
            </div>
        </div>
    </div>
</x-superadmin-layout> 