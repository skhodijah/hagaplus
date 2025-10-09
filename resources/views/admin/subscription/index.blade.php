<x-admin-layout>
    <div class="py-2">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <x-page-header
                title="Subscription Management"
                subtitle="Kelola subscription dan pembayaran instansi Anda"
                :show-period-filter="false"
            />

            <!-- Current Subscription Status -->
            <div class="mb-6">
                <x-section-card title="Status Subscription Saat Ini">
                    @if($currentSubscription)
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div class="bg-gradient-to-r from-blue-50 to-blue-100 dark:from-blue-900/20 dark:to-blue-800/20 p-6 rounded-lg">
                                <div class="flex items-center">
                                    <div class="p-3 bg-blue-500 rounded-full">
                                        <i class="fa-solid fa-crown text-white"></i>
                                    </div>
                                    <div class="ml-4">
                                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Paket Aktif</h3>
                                        <p class="text-blue-600 dark:text-blue-400 font-medium">{{ $currentSubscription->package_name ?? 'N/A' }}</p>
                                    </div>
                                </div>
                            </div>

                            <div class="bg-gradient-to-r from-green-50 to-green-100 dark:from-green-900/20 dark:to-green-800/20 p-6 rounded-lg">
                                <div class="flex items-center">
                                    <div class="p-3 bg-green-500 rounded-full">
                                        <i class="fa-solid fa-calendar-check text-white"></i>
                                    </div>
                                    <div class="ml-4">
                                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Berakhir Pada</h3>
                                        <p class="text-green-600 dark:text-green-400 font-medium">{{ \Carbon\Carbon::parse($currentSubscription->end_date)->format('d M Y') }}</p>
                                    </div>
                                </div>
                            </div>

                            <div class="bg-gradient-to-r from-purple-50 to-purple-100 dark:from-purple-900/20 dark:to-purple-800/20 p-6 rounded-lg">
                                <div class="flex items-center">
                                    <div class="p-3 bg-purple-500 rounded-full">
                                        <i class="fa-solid fa-dollar-sign text-white"></i>
                                    </div>
                                    <div class="ml-4">
                                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Biaya Bulanan</h3>
                                        <p class="text-purple-600 dark:text-purple-400 font-medium">Rp {{ number_format($currentSubscription->price, 0, ',', '.') }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Days remaining warning -->
                        @php
                            $daysRemaining = \Carbon\Carbon::parse($currentSubscription->end_date)->diffInDays(now());
                            $isExpiringSoon = $daysRemaining <= 30;
                        @endphp

                        @if($isExpiringSoon)
                            <div class="mt-4 p-4 bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-lg">
                                <div class="flex items-center">
                                    <i class="fa-solid fa-exclamation-triangle text-yellow-600 dark:text-yellow-400 mr-3"></i>
                                    <div>
                                        <h4 class="text-yellow-800 dark:text-yellow-200 font-medium">Subscription akan berakhir dalam {{ $daysRemaining }} hari</h4>
                                        <p class="text-yellow-700 dark:text-yellow-300 text-sm">Pertimbangkan untuk memperpanjang subscription Anda.</p>
                                    </div>
                                </div>
                            </div>
                        @endif
                    @else
                        <div class="text-center py-12">
                            <i class="fa-solid fa-exclamation-circle text-gray-400 text-4xl mb-4"></i>
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">Tidak Ada Subscription Aktif</h3>
                            <p class="text-gray-500 dark:text-gray-400">Instansi Anda belum memiliki subscription aktif.</p>
                        </div>
                    @endif
                </x-section-card>
            </div>

            <!-- Action Buttons -->
            <div class="mb-6">
                <x-section-card title="Kelola Subscription">
                    @if($instansi)
                        <div class="space-y-6">
                            <!-- Extension Request Form -->
                            <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-4">
                                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4 flex items-center">
                                    <i class="fa-solid fa-calendar-plus text-blue-500 mr-2"></i>
                                    Perpanjangan Subscription
                                </h3>
                                <form method="POST" action="{{ route('admin.subscription.extend') }}" class="space-y-4">
                                    @csrf
                                    <div>
                                        <label for="extension_months" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                            Lama Perpanjangan (Bulan) <span class="text-red-500">*</span>
                                        </label>
                                        <select name="extension_months" id="extension_months" required
                                                class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                                            <option value="">Pilih Lama Perpanjangan</option>
                                            <option value="1">1 Bulan</option>
                                            <option value="3">3 Bulan</option>
                                            <option value="6">6 Bulan</option>
                                            <option value="12">12 Bulan</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label for="extension_notes" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                            Catatan (Opsional)
                                        </label>
                                        <textarea name="notes" id="extension_notes" rows="2"
                                                  class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
                                                  placeholder="Tambahkan catatan jika diperlukan..."></textarea>
                                    </div>
                                    <button type="submit" class="w-full bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg font-medium transition-colors">
                                        <i class="fa-solid fa-calendar-plus mr-2"></i>Ajukan Perpanjangan
                                    </button>
                                </form>
                            </div>

                            <!-- Upgrade Request Form -->
                            <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-4">
                                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4 flex items-center">
                                    <i class="fa-solid fa-arrow-up text-green-500 mr-2"></i>
                                    Upgrade Subscription
                                </h3>
                                <form method="POST" action="{{ route('admin.subscription.upgrade') }}" class="space-y-4">
                                    @csrf
                                    <div>
                                        <label for="target_package_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                            Pilih Paket Tujuan <span class="text-red-500">*</span>
                                        </label>
                                        <select name="target_package_id" id="target_package_id" required
                                                class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-green-500 focus:border-green-500 dark:bg-gray-700 dark:text-white">
                                            <option value="">Pilih Paket</option>
                                            @foreach($packages as $package)
                                                <option value="{{ $package->id }}" {{ ($currentSubscription && $currentSubscription->package_id == $package->id) ? 'disabled' : '' }}>
                                                    {{ $package->name }} - Rp {{ number_format($package->price, 0, ',', '.') }}/bulan
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div>
                                        <label for="upgrade_notes" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                            Catatan (Opsional)
                                        </label>
                                        <textarea name="notes" id="upgrade_notes" rows="2"
                                                  class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-green-500 focus:border-green-500 dark:bg-gray-700 dark:text-white"
                                                  placeholder="Tambahkan catatan jika diperlukan..."></textarea>
                                    </div>
                                    <button type="submit" class="w-full bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg font-medium transition-colors">
                                        <i class="fa-solid fa-arrow-up mr-2"></i>Ajukan Upgrade
                                    </button>
                                </form>
                            </div>

                            <!-- Combined Request Form -->
                            <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-4">
                                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4 flex items-center">
                                    <i class="fa-solid fa-sync text-purple-500 mr-2"></i>
                                    Perpanjangan + Upgrade
                                </h3>
                                <form method="POST" action="{{ route('admin.subscription.request') }}" class="space-y-4">
                                    @csrf
                                    <input type="hidden" name="request_type" value="both">
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div>
                                            <label for="both_extension_months" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                                Lama Perpanjangan (Bulan) <span class="text-red-500">*</span>
                                            </label>
                                            <select name="extension_months" id="both_extension_months" required
                                                    class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-purple-500 focus:border-purple-500 dark:bg-gray-700 dark:text-white">
                                                <option value="">Pilih Lama Perpanjangan</option>
                                                <option value="1">1 Bulan</option>
                                                <option value="3">3 Bulan</option>
                                                <option value="6">6 Bulan</option>
                                                <option value="12">12 Bulan</option>
                                            </select>
                                        </div>
                                        <div>
                                            <label for="both_target_package_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                                Pilih Paket Tujuan <span class="text-red-500">*</span>
                                            </label>
                                            <select name="target_package_id" id="both_target_package_id" required
                                                    class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-purple-500 focus:border-purple-500 dark:bg-gray-700 dark:text-white">
                                                <option value="">Pilih Paket</option>
                                                @foreach($packages as $package)
                                                    <option value="{{ $package->id }}" {{ ($currentSubscription && $currentSubscription->package_id == $package->id) ? 'disabled' : '' }}>
                                                        {{ $package->name }} - Rp {{ number_format($package->price, 0, ',', '.') }}/bulan
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div>
                                        <label for="combined_notes" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                            Catatan (Opsional)
                                        </label>
                                        <textarea name="notes" id="combined_notes" rows="2"
                                                  class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-purple-500 focus:border-purple-500 dark:bg-gray-700 dark:text-white"
                                                  placeholder="Tambahkan catatan jika diperlukan..."></textarea>
                                    </div>
                                    <button type="submit" class="w-full bg-purple-500 hover:bg-purple-600 text-white px-4 py-2 rounded-lg font-medium transition-colors">
                                        <i class="fa-solid fa-sync mr-2"></i>Ajukan Perpanjangan + Upgrade
                                    </button>
                                </form>
                            </div>
                        </div>
                    @else
                        <div class="text-center py-8">
                            <i class="fa-solid fa-info-circle text-blue-500 text-3xl mb-4"></i>
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">Belum Ada Subscription</h3>
                            <p class="text-gray-500 dark:text-gray-400 mb-4">Instansi Anda belum memiliki subscription. Silakan hubungi superadmin untuk membuat subscription baru.</p>
                            <a href="{{ route('admin.notifications.index') }}" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg font-medium transition-colors">
                                <i class="fa-solid fa-bell mr-2"></i>Lihat Notifikasi
                            </a>
                        </div>
                    @endif
                </x-section-card>
            </div>

            <!-- Subscription History -->
            <div class="mb-6">
                <x-section-card title="Riwayat Subscription">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-800">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Paket</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Tanggal Mulai</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Tanggal Berakhir</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Harga</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-900 divide-y divide-gray-200 dark:divide-gray-700">
                                @forelse($subscriptionHistory as $subscription)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                            {{ $subscription->package_name ?? 'N/A' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                                @if($subscription->status == 'active') bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300
                                                @elseif($subscription->status == 'inactive') bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-300
                                                @elseif($subscription->status == 'expired') bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300
                                                @else bg-gray-100 text-gray-800 dark:bg-gray-900/30 dark:text-gray-300 @endif">
                                                {{ ucfirst($subscription->status) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                            {{ \Carbon\Carbon::parse($subscription->start_date)->format('d M Y') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                            {{ \Carbon\Carbon::parse($subscription->end_date)->format('d M Y') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                            Rp {{ number_format($subscription->price, 0, ',', '.') }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                                            Belum ada riwayat subscription
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </x-section-card>
            </div>

            <!-- Payment History -->
            <x-section-card title="Riwayat Pembayaran">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-800">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Tanggal</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Paket</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Metode</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Jumlah</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-900 divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse($paymentHistory as $payment)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                        {{ \Carbon\Carbon::parse($payment->created_at)->format('d M Y H:i') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                        {{ $payment->package_name }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                        {{ ucfirst(str_replace('_', ' ', $payment->payment_method)) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                            @if($payment->payment_status == 'paid') bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300
                                            @elseif($payment->payment_status == 'pending') bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-300
                                            @else bg-gray-100 text-gray-800 dark:bg-gray-900/30 dark:text-gray-300 @endif">
                                            {{ ucfirst($payment->payment_status) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                        Rp {{ number_format($payment->amount, 0, ',', '.') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        @if($payment->payment_status == 'pending')
                                            <div class="flex space-x-2">
                                                @if($payment->payment_proof)
                                                    <button onclick="showPaymentDetail({{ $payment->id }})" class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded text-xs font-medium transition-colors">
                                                        Detail
                                                    </button>
                                                @else
                                                    <a href="{{ route('admin.subscription.transaction', $payment->id) }}" class="bg-green-500 hover:bg-green-600 text-white px-3 py-1 rounded text-xs font-medium transition-colors">
                                                        Continue Payment
                                                    </a>
                                                @endif
                                                <form method="POST" action="{{ route('admin.subscription.cancel-payment', $payment->id) }}" class="inline"
                                                      onsubmit="return confirm('Apakah Anda yakin ingin membatalkan permintaan ini?')">
                                                    @csrf
                                                    <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-xs font-medium transition-colors">
                                                        Batalkan
                                                    </button>
                                                </form>
                                            </div>
                                        @else
                                            <span class="text-gray-400">-</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                                        Belum ada riwayat pembayaran
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </x-section-card>
        </div>
    </div>

    <script>
        function showPaymentDetail(paymentId) {
            // Find the payment data from the payments array
            const payments = @json($paymentHistory);
            const payment = payments.find(p => p.id == paymentId);

            if (payment) {
                document.getElementById('paymentDetailModal').classList.remove('hidden');
                document.getElementById('modalTitle').textContent = 'Detail Pembayaran - ' + payment.transaction_id;
                document.getElementById('detailTransactionId').textContent = payment.transaction_id;
                document.getElementById('detailPackage').textContent = payment.package_name;
                document.getElementById('detailAmount').textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(payment.amount);
                document.getElementById('detailStatus').textContent = payment.payment_status;
                document.getElementById('detailMethod').textContent = payment.payment_method;
                document.getElementById('detailDate').textContent = new Date(payment.created_at).toLocaleString('id-ID');
                document.getElementById('detailNotes').textContent = payment.notes || '-';
            }
        }

        function closePaymentDetailModal() {
            document.getElementById('paymentDetailModal').classList.add('hidden');
        }
    </script>

    <!-- Payment Detail Modal -->
    <div id="paymentDetailModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <div class="flex items-center justify-between mb-4">
                    <h3 id="modalTitle" class="text-lg font-medium text-gray-900">Detail Pembayaran</h3>
                    <button onclick="closePaymentDetailModal()" class="text-gray-400 hover:text-gray-600">
                        <i class="fa-solid fa-times"></i>
                    </button>
                </div>

                <div class="space-y-3">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Transaction ID</label>
                        <p id="detailTransactionId" class="text-sm text-gray-900">-</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Paket</label>
                        <p id="detailPackage" class="text-sm text-gray-900">-</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Jumlah</label>
                        <p id="detailAmount" class="text-sm text-gray-900">-</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Status</label>
                        <p id="detailStatus" class="text-sm text-gray-900">-</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Metode Pembayaran</label>
                        <p id="detailMethod" class="text-sm text-gray-900">-</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Tanggal</label>
                        <p id="detailDate" class="text-sm text-gray-900">-</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Catatan</label>
                        <p id="detailNotes" class="text-sm text-gray-900">-</p>
                    </div>
                </div>

                <div class="flex justify-end mt-4">
                    <button onclick="closePaymentDetailModal()" class="px-4 py-2 bg-gray-500 text-white text-sm font-medium rounded hover:bg-gray-600">
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>