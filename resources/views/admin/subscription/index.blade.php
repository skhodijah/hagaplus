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
            <x-section-card title="Riwayat Pembayaran" class="mb-8">
                <div class="overflow-x-auto rounded-lg border border-gray-200 dark:border-gray-700">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gradient-to-r from-blue-50 to-blue-100 dark:from-gray-800 dark:to-gray-900">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 dark:text-gray-300 uppercase tracking-wider">Tanggal</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 dark:text-gray-300 uppercase tracking-wider">Paket</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 dark:text-gray-300 uppercase tracking-wider">Metode</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 dark:text-gray-300 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 dark:text-gray-300 uppercase tracking-wider">Jumlah</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-600 dark:text-gray-300 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-900 divide-y divide-gray-200 dark:divide-gray-800">
                            @forelse($paymentHistory as $payment)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-10 w-10 flex items-center justify-center rounded-full bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 mr-3">
                                                <i class="fa-solid fa-calendar-day text-sm"></i>
                                            </div>
                                            <div>
                                                <div class="text-sm font-medium text-gray-900 dark:text-white">
                                                    {{ \Carbon\Carbon::parse($payment->created_at)->format('d M Y') }}
                                                </div>
                                                <div class="text-xs text-gray-500 dark:text-gray-400">
                                                    {{ \Carbon\Carbon::parse($payment->created_at)->format('H:i') }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900 dark:text-white">
                                            {{ $payment->package_name ?? 'N/A' }}
                                        </div>
                                        @if(isset($payment->target_package_name) && $payment->target_package_name && $payment->target_package_name != $payment->package_name)
                                            <div class="text-xs text-gray-500 dark:text-gray-400">
                                                <i class="fa-solid fa-arrow-right text-green-500 mr-1"></i>
                                                {{ $payment->target_package_name }}
                                            </div>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            @php
                                                $methodIcon = [
                                                    'bank_transfer' => 'fa-building-columns',
                                                    'credit_card' => 'fa-credit-card',
                                                    'qris' => 'fa-qrcode',
                                                    'ewallet' => 'fa-wallet',
                                                    'pending' => 'fa-clock',
                                                ][$payment->payment_method] ?? 'fa-money-bill-wave';
                                            @endphp
                                            <i class="fa-solid {{ $methodIcon }} text-gray-500 dark:text-gray-400 mr-2"></i>
                                            <span class="text-sm text-gray-900 dark:text-white">
                                                {{ $payment->payment_method_name ?? ucfirst(str_replace('_', ' ', $payment->payment_method ?? 'N/A')) }}
                                            </span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @php
                                            $statusConfig = [
                                                'paid' => ['bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300', 'fa-check-circle'],
                                                'pending' => ['bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-300', 'fa-clock'],
                                                'rejected' => ['bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300', 'fa-times-circle'],
                                                'expired' => ['bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300', 'fa-calendar-xmark'],
                                            ][$payment->payment_status] ?? ['bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300', 'fa-info-circle'];
                                        @endphp
                                        <span class="px-3 py-1 inline-flex items-center text-xs font-medium rounded-full {{ $statusConfig[0] }}">
                                            <i class="fa-solid {{ $statusConfig[1] }} mr-1.5"></i>
                                            {{ ucfirst($payment->payment_status) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-semibold text-gray-900 dark:text-white">
                                            Rp {{ number_format($payment->amount, 0, ',', '.') }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <div class="flex justify-end space-x-2">
                                            <button onclick="showPaymentDetail({{ $payment->id }})" 
                                                    class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                                                <i class="fa-solid fa-eye mr-1.5"></i>
                                                Detail
                                            </button>
                                            @if($payment->payment_status == 'pending' && !$payment->payment_proof)
                                                <a href="{{ route('admin.subscription.transaction', $payment->id) }}" class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md shadow-sm text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors">
                                                    <i class="fa-solid fa-credit-card mr-1.5"></i>
                                                    Lanjutkan Pembayaran
                                                </a>
                                                <form method="POST" action="{{ route('admin.subscription.cancel-payment', $payment->id) }}" class="inline"
                                                      onsubmit="return confirm('Apakah Anda yakin ingin membatalkan permintaan ini?')">
                                                    @csrf
                                                    <button type="submit" class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md shadow-sm text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors">
                                                        <i class="fa-solid fa-times-circle mr-1.5"></i>
                                                        Batalkan
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
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
                const modal = document.getElementById('paymentDetailModal');
                modal.classList.remove('hidden');
                
                // Set basic info
                document.getElementById('modalTitle').textContent = 'Detail Pembayaran - ' + (payment.transaction_id || '#' + payment.id);
                document.getElementById('detailTransactionId').textContent = payment.transaction_id || 'N/A';
                document.getElementById('detailPackage').textContent = payment.package_name || 'N/A';
                
                // Handle package upgrade display
                const targetPackageEl = document.getElementById('detailTargetPackage');
                const targetPackageNameEl = document.getElementById('targetPackageName');
                if (payment.target_package_name && payment.target_package_name !== payment.package_name) {
                    targetPackageNameEl.textContent = payment.target_package_name;
                    targetPackageEl.classList.remove('hidden');
                } else {
                    targetPackageEl.classList.add('hidden');
                }
                
                // Set amount with currency formatting
                document.getElementById('detailAmount').textContent = payment.amount 
                    ? 'Rp ' + new Intl.NumberFormat('id-ID').format(payment.amount)
                    : 'Rp 0';
                
                // Set status with appropriate styling
                const statusBadge = document.getElementById('detailStatusBadge');
                const statusText = payment.payment_status 
                    ? payment.payment_status.charAt(0).toUpperCase() + payment.payment_status.slice(1)
                    : 'Tidak Diketahui';
                document.getElementById('detailStatus').textContent = statusText;
                
                // Update status badge color based on status
                statusBadge.className = 'inline-flex items-center px-4 py-2 rounded-full text-sm font-medium ';
                if (payment.payment_status === 'paid') {
                    statusBadge.className += 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200';
                } else if (payment.payment_status === 'pending') {
                    statusBadge.className += 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200';
                } else if (payment.payment_status === 'rejected' || payment.payment_status === 'failed') {
                    statusBadge.className += 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200';
                } else {
                    statusBadge.className += 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300';
                }
                
                // Set payment method with icon
                const methodEl = document.getElementById('detailMethod');
                const methodIcons = {
                    'bank_transfer': 'fa-building-columns',
                    'credit_card': 'fa-credit-card',
                    'qris': 'fa-qrcode',
                    'ewallet': 'fa-wallet',
                    'virtual_account': 'fa-building-columns',
                    'retail': 'fa-store',
                    'cstore': 'fa-store-alt',
                    'other': 'fa-money-bill-wave'
                };
                
                if (payment.payment_method) {
                    const iconClass = methodIcons[payment.payment_method] || 'fa-credit-card';
                    methodEl.innerHTML = `
                        <i class="fas ${iconClass} mr-2 text-blue-500"></i>
                        <span class="text-sm text-gray-900 dark:text-white">
                            ${payment.payment_method_name || 
                              (payment.payment_method.charAt(0).toUpperCase() + payment.payment_method.slice(1).replace(/_/g, ' '))}
                        </span>
                    `;
                } else {
                    methodEl.innerHTML = '<span class="text-sm text-gray-500">Tidak tersedia</span>';
                }
                
                // Format dates
                if (payment.created_at) {
                    const date = new Date(payment.created_at);
                    document.getElementById('detailDate').textContent = date.toLocaleDateString('id-ID', {
                        weekday: 'long',
                        day: 'numeric',
                        month: 'long',
                        year: 'numeric',
                        hour: '2-digit',
                        minute: '2-digit'
                    });
                }
                
                // Set expiry date if available
                const expiryDateEl = document.getElementById('detailExpiryDate');
                if (payment.expiry_date) {
                    const expiryDate = new Date(payment.expiry_date);
                    expiryDateEl.textContent = expiryDate.toLocaleDateString('id-ID', {
                        weekday: 'long',
                        day: 'numeric',
                        month: 'long',
                        year: 'numeric',
                        hour: '2-digit',
                        minute: '2-digit'
                    });
                } else {
                    expiryDateEl.textContent = 'Tidak tersedia';
                }
                
                // Handle payment proof
                const paymentProofSection = document.getElementById('paymentProofSection');
                const paymentProofImage = document.getElementById('paymentProofImage');
                const paymentProofContainer = document.getElementById('paymentProofImageContainer');
                const noPaymentProof = document.getElementById('noPaymentProof');
                
                if (payment.payment_proof) {
                    paymentProofImage.src = '{{ asset("storage") }}/' + payment.payment_proof;
                    paymentProofContainer.classList.remove('hidden');
                    noPaymentProof.classList.add('hidden');
                    
                    // Set upload date if available
                    const proofDateEl = document.getElementById('paymentProofDate');
                    if (payment.payment_proof_uploaded_at || payment.updated_at) {
                        const proofDate = new Date(payment.payment_proof_uploaded_at || payment.updated_at);
                        proofDateEl.textContent = 'Diunggah: ' + proofDate.toLocaleString('id-ID', {
                            day: 'numeric',
                            month: 'long',
                            year: 'numeric',
                            hour: '2-digit',
                            minute: '2-digit'
                        });
                    }
                } else {
                    paymentProofContainer.classList.add('hidden');
                    noPaymentProof.classList.remove('hidden');
                }
                
                // Handle notes
                const notesEl = document.getElementById('detailNotes');
                const notesSection = document.getElementById('notesSection');
                
                if (payment.notes) {
                    notesEl.textContent = payment.notes;
                    notesSection.classList.remove('hidden');
                } else {
                    notesEl.textContent = 'Tidak ada catatan';
                    notesSection.classList.add('hidden');
                }
                
                // Add animation
                setTimeout(() => {
                    modal.querySelector('.transform').classList.add('opacity-100', 'scale-100');
                }, 10);
            }
        }

        function closePaymentDetailModal() {
            const modal = document.getElementById('paymentDetailModal');
            modal.querySelector('.transform').classList.remove('opacity-100', 'scale-100');
            setTimeout(() => {
                modal.classList.add('hidden');
            }, 300);
        }
    </script>

    <!-- Payment Detail Modal -->
    <div id="paymentDetailModal" class="fixed inset-0 bg-black bg-opacity-50 overflow-y-auto h-full w-full hidden z-50 transition-opacity duration-300">
        <div class="relative top-10 mx-auto p-0 w-full max-w-2xl">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl overflow-hidden transform transition-all">
                <!-- Modal Header -->
                <div class="px-6 py-4 bg-gradient-to-r from-blue-600 to-blue-500">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <i class="fas fa-receipt text-white text-xl mr-3"></i>
                            <h3 id="modalTitle" class="text-lg font-semibold text-white">Detail Transaksi</h3>
                        </div>
                        <button onclick="closePaymentDetailModal()" class="text-white hover:text-gray-200 focus:outline-none">
                            <i class="fas fa-times text-xl"></i>
                        </button>
                    </div>
                </div>

                <!-- Modal Body -->
                <div class="p-6">
                    <!-- Transaction Status Badge -->
                    <div class="mb-6 text-center">
                        <span id="detailStatusBadge" class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium">
                            <i class="fas fa-circle-notch fa-spin mr-2"></i>
                            <span id="detailStatus">Memuat...</span>
                        </span>
                    </div>

                    <!-- Transaction Details -->
                    <div class="space-y-4">
                        <!-- Transaction ID -->
                        <div class="flex justify-between py-2 border-b border-gray-200 dark:border-gray-700">
                            <span class="text-sm font-medium text-gray-500 dark:text-gray-400">ID Transaksi</span>
                            <span id="detailTransactionId" class="text-sm text-gray-900 dark:text-white font-mono">-</span>
                        </div>

                        <!-- Package Info -->
                        <div class="py-2 border-b border-gray-200 dark:border-gray-700">
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Paket Berlangganan</p>
                            <div class="flex items-center">
                                <span id="detailPackage" class="px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">-</span>
                                <span id="detailTargetPackage" class="ml-2 hidden items-center text-sm text-gray-600 dark:text-gray-300">
                                    <i class="fas fa-arrow-right mx-1 text-xs"></i>
                                    <span id="targetPackageName" class="px-2 py-0.5 rounded-full bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300"></span>
                                </span>
                            </div>
                        </div>

                        <!-- Amount -->
                        <div class="flex justify-between py-2 border-b border-gray-200 dark:border-gray-700 items-center">
                            <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Jumlah</span>
                            <span id="detailAmount" class="text-lg font-bold text-gray-900 dark:text-white">-</span>
                        </div>

                        <!-- Payment Method -->
                        <div class="py-2 border-b border-gray-200 dark:border-gray-700">
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Metode Pembayaran</p>
                            <div id="detailMethod" class="flex items-center">
                                <i class="fas fa-credit-card mr-2 text-gray-500"></i>
                                <span class="text-sm text-gray-900 dark:text-white">-</span>
                            </div>
                        </div>

                        <!-- Dates -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 py-2">
                            <div>
                                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Tanggal Transaksi</p>
                                <div class="mt-1 flex items-center">
                                    <i class="far fa-calendar-alt mr-2 text-gray-500"></i>
                                    <span id="detailDate" class="text-sm text-gray-900 dark:text-white">-</span>
                                </div>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Batas Pembayaran</p>
                                <div class="mt-1 flex items-center">
                                    <i class="far fa-clock mr-2 text-gray-500"></i>
                                    <span id="detailExpiryDate" class="text-sm text-gray-900 dark:text-white">-</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Payment Proof Section -->
                    <div id="paymentProofSection" class="mt-6 pt-6 border-t border-gray-200 dark:border-gray-700">
                        <p class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">Bukti Pembayaran</p>
                        <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg text-center">
                            <div id="noPaymentProof" class="py-8">
                                <i class="fas fa-image text-4xl text-gray-300 dark:text-gray-500 mb-2"></i>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Tidak ada bukti pembayaran</p>
                            </div>
                            <div id="paymentProofImageContainer" class="hidden">
                                <img id="paymentProofImage" src="#" alt="Bukti Pembayaran" class="max-h-64 mx-auto rounded">
                                <p id="paymentProofDate" class="mt-2 text-xs text-gray-500 dark:text-gray-400">Diunggah: -</p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Notes Section -->
                    <div id="notesSection" class="mt-6 pt-6 border-t border-gray-200 dark:border-gray-700">
                        <p class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Catatan</p>
                        <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                            <p id="detailNotes" class="text-sm text-gray-700 dark:text-gray-300">-</p>
                        </div>
                    </div>
                </div>

                <!-- Modal Footer -->
                <div class="px-6 py-4 bg-gray-50 dark:bg-gray-700/50 border-t border-gray-200 dark:border-gray-700 flex justify-end">
                    <button type="button" onclick="closePaymentDetailModal()" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-md text-sm font-medium focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>