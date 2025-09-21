<div class="flex flex-col">
    <!-- Large Screen Layout: Photo centered, then 2-column identity below -->
    <div class="hidden lg:block">
        <!-- Profile Photo -->
        <div class="flex justify-center mb-6">
            <div class="aspect-square w-full p-5 pb-0 overflow-hidden">
                <img src="https://i.pinimg.com/736x/6d/27/20/6d27206ea8c2a4c0fe857590f2c2bb06.jpg" alt="Profile Photo"
                    class="h-full w-full object-cover rounded-xl shadow-lg">
            </div>
        </div>

        <!-- Identity Section - 2 Columns -->
        <div class="grid grid-cols-2 px-5 gap-4">
            <div class="col-span-1">
                <!-- Name & Position -->
                <div>
                    <h1 class="text-2xl lg:text-3xl font-bold text-neutral-900 dark:text-neutral-100 mb-1">
                        Lukman Muludin
                    </h1>
                    <p class="text-sm lg:text-base text-neutral-600 dark:text-neutral-400 font-medium mb-2">
                        UX/UI Designer
                    </p>
                </div>
            </div>
            <div class="col-span-1 text-right">
                <span
                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400">
                    <span class="w-1.5 h-1.5 bg-green-400 rounded-full mr-1.5"></span>
                    Active
                </span>
            </div>
            <div class="col-span-2">
                <!-- Email -->
                <div class="bg-neutral-50 dark:bg-neutral-800/50 rounded-lg">
                    <div
                        class="text-xs font-medium text-neutral-500 dark:text-neutral-400 uppercase tracking-wide mb-1">
                        Email
                    </div>
                    <div class="flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                            class="h-4 w-4 text-neutral-400">
                            <path
                                d="M1.5 8.67v8.58a3 3 0 003 3h15a3 3 0 003-3V8.67l-8.928 5.493a3 3 0 01-3.144 0L1.5 8.67z" />
                            <path
                                d="M22.5 6.908V6.75a3 3 0 00-3-3h-15a3 3 0 00-3 3v.158l9.714 5.978a1.5 1.5 0 001.572 0L22.5 6.908z" />
                        </svg>
                        <span
                            class="font-medium text-neutral-900 dark:text-neutral-100 break-all">lukmanmauludin831@gmail.com</span>
                    </div>
                </div>

                <!-- Contact -->
                <div class="bg-neutral-50 dark:bg-neutral-800/50 rounded-lg mt-2">
                    <div
                        class="text-xs font-medium text-neutral-500 dark:text-neutral-400 uppercase tracking-wide mb-1">
                        Contact
                    </div>
                    <div class="flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                            class="h-4 w-4 text-neutral-400">
                            <path fill-rule="evenodd"
                                d="M1.5 4.5a3 3 0 013-3h1.372c.86 0 1.61.586 1.819 1.42l1.105 4.423a1.875 1.875 0 01-.694 1.955l-1.293.97c-.135.101-.164.249-.126.352a11.285 11.285 0 006.697 6.697c.103.038.25.009.352-.126l.97-1.293a1.875 1.875 0 011.955-.694l4.423 1.105c.834.209 1.42.959 1.42 1.82V19.5a3 3 0 01-3 3h-2.25C6.477 22.5 2.25 18.273 2.25 12.75V4.5z"
                                clip-rule="evenodd" />
                        </svg>
                        <span class="font-medium text-neutral-900 dark:text-neutral-100">089529204605</span>
                    </div>
                </div>
            </div>
            <!-- Left Column -->
            <div class="space-y-4">


            </div>

            <!-- Right Column -->
            <div class="space-y-4">

            </div>
        </div>
    </div>

    <!-- Small Screen Layout: Photo left, Identity right -->
    <div class="lg:hidden">
        <div class="grid grid-cols-5 gap-4">
            <!-- Profile Photo - Left -->
            <div class="col-span-2 p-3">
                <div class="aspect-square overflow-hidden">
                    <img src="https://i.pinimg.com/736x/6d/27/20/6d27206ea8c2a4c0fe857590f2c2bb06.jpg"
                        alt="Profile Photo" class="h-full w-full object-cover rounded-xl shadow-lg">
                </div>
            </div>

            <!-- Identity - Right -->
            <div class="col-span-3 space-y-3 p-3">
                <!-- Name & Position with Badge -->
                <div class="flex justify-between items-start">
                    <div class="flex-1">
                        <h1 class="text-xl font-bold text-neutral-900 dark:text-neutral-100 mb-1">
                            Lukman Muludin
                        </h1>
                        <p class="text-sm text-neutral-600 dark:text-neutral-400 font-medium mb-2">
                            UX/UI Designer
                        </p>
                    </div>
                    <span
                        class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400 ml-2 flex-shrink-0">
                        <span class="w-1.5 h-1.5 bg-green-400 rounded-full mr-1.5"></span>
                        Active
                    </span>
                </div>

                <!-- Contact Info - Visible on screens >= 510px -->
                <div class="space-y-2 xs:block hidden">
                    <!-- Phone -->
                    <div class="text-xs">
                        <div class="text-neutral-500 dark:text-neutral-400 mb-1">Phone</div>
                        <div class="font-medium text-neutral-900 dark:text-neutral-100">089529204605</div>
                    </div>

                    <!-- Email -->
                    <div class="text-xs">
                        <div class="text-neutral-500 dark:text-neutral-400 mb-1">Email</div>
                        <div class="font-medium text-neutral-900 dark:text-neutral-100 break-all">
                            lukmanmauludin831@gmail.com</div>
                    </div>
                </div>
            </div>

            <!-- Contact Info - Full width on very small screens (below 510px) -->
            <div class="col-span-5 space-y-2 p-3 xs:hidden">
                <!-- Phone -->
                <div class="text-xs">
                    <div class="text-neutral-500 dark:text-neutral-400 mb-1">Phone</div>
                    <div class="font-medium text-neutral-900 dark:text-neutral-100">089529204605</div>
                </div>

                <!-- Email -->
                <div class="text-xs">
                    <div class="text-neutral-500 dark:text-neutral-400 mb-1">Email</div>
                    <div class="font-medium text-neutral-900 dark:text-neutral-100 break-all">
                        lukmanmauludin831@gmail.com</div>
                </div>
            </div>
        </div>
    </div>
</div>
