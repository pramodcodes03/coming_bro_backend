{{--
    Assign Recharge Plan modal (shared by driver detail + driver list).

    Host page must provide these Alpine state vars on an ancestor x-data:
      planOpen        (bool)   modal visibility
      plans           (array)  [{id,label,rides,price,validity_days}]
      selectedPlanId  (string) currently chosen plan id
      selectedPlan    (getter) the matching plan object or null
      planLabel(p)    (method) formats a plan into its dropdown label
      driverName      (string) shown in the header
      driverRemaining (number) current remaining rides (for the live preview)
      assignAction    (string) form POST url for this driver
--}}
<div x-show="planOpen" x-cloak
     class="fixed inset-0 z-[60] flex items-center justify-center p-4"
     x-transition.opacity>
    <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" @click="planOpen = false"></div>

    <div class="relative w-full max-w-md bg-white shadow-2xl dark:bg-gray-900 rounded-2xl"
         x-show="planOpen" x-transition @keydown.escape.window="planOpen = false">
        <!-- Gradient header (top corners rounded; no overflow-hidden so the
             custom dropdown is never clipped). -->
        <div class="relative px-6 py-5 text-white bg-gradient-to-r from-[#018DBD] to-[#13C3C3] rounded-t-2xl">
            <button type="button" @click="planOpen = false"
                class="absolute p-1 rounded-lg top-4 right-4 hover:bg-white/20 transition">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
            <div class="flex items-center gap-3">
                <div class="flex items-center justify-center rounded-full w-11 h-11 bg-white/20">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                </div>
                <div>
                    <h3 class="text-lg font-bold">Assign Recharge Plan</h3>
                    <p class="text-sm text-white/80">to <span x-text="driverName"></span></p>
                </div>
            </div>
        </div>

        <template x-if="plans.length === 0">
            <div class="px-6 py-8 text-center">
                <p class="text-sm text-gray-600 dark:text-gray-300">No active recharge plans.</p>
                <a href="{{ route('admin.recharge-plans.create') }}" class="inline-block mt-2 text-sm font-semibold text-primary hover:underline">+ Create a plan</a>
            </div>
        </template>

        <template x-if="plans.length > 0">
            <form method="POST" :action="assignAction" class="px-6 py-5 space-y-5">
                @csrf

                <!-- Custom dropdown (renders INSIDE the modal — no native select overlay) -->
                <div x-data="{ menu: false }" @click.outside="menu = false" class="relative">
                    <label class="block mb-2 text-sm font-semibold text-gray-700 dark:text-gray-300">Choose a plan</label>
                    <button type="button" @click="menu = !menu"
                        class="flex items-center justify-between w-full px-4 py-3 text-sm text-left text-gray-900 bg-gray-50 border border-gray-200 rounded-lg dark:bg-gray-800 dark:border-gray-700 dark:text-white focus:ring-2 focus:ring-[#018DBD] focus:border-[#018DBD] outline-none">
                        <span x-text="selectedPlan ? planLabel(selectedPlan) : '— Select a plan —'"
                              :class="selectedPlan ? '' : 'text-gray-400'"></span>
                        <svg class="w-4 h-4 text-gray-400 shrink-0 transition-transform" :class="menu ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </button>

                    <div x-show="menu" x-cloak x-transition
                        class="absolute z-20 w-full mt-1 overflow-auto bg-white border border-gray-200 shadow-lg max-h-60 rounded-lg dark:bg-gray-800 dark:border-gray-700">
                        <template x-for="p in plans" :key="p.id">
                            <button type="button" @click="selectedPlanId = p.id; menu = false"
                                class="block w-full px-4 py-2.5 text-sm text-left transition hover:bg-[#018DBD]/10 dark:hover:bg-[#018DBD]/20"
                                :class="selectedPlanId == p.id ? 'bg-[#018DBD]/10 text-[#018DBD] font-semibold' : 'text-gray-700 dark:text-gray-200'"
                                x-text="planLabel(p)"></button>
                        </template>
                    </div>

                    <input type="hidden" name="recharge_plan_id" :value="selectedPlanId">
                </div>

                <!-- Selected plan summary -->
                <template x-if="selectedPlan">
                    <div class="grid grid-cols-3 gap-3">
                        <div class="p-3 text-center rounded-lg bg-gray-50 dark:bg-gray-800/60">
                            <p class="text-xs text-gray-500 dark:text-gray-400">Rides</p>
                            <p class="text-lg font-bold text-gray-900 dark:text-white" x-text="selectedPlan.rides"></p>
                        </div>
                        <div class="p-3 text-center rounded-lg bg-gray-50 dark:bg-gray-800/60">
                            <p class="text-xs text-gray-500 dark:text-gray-400">Price</p>
                            <p class="text-lg font-bold text-gray-900 dark:text-white" x-text="'₹' + selectedPlan.price.toFixed(2)"></p>
                        </div>
                        <div class="p-3 text-center rounded-lg bg-gray-50 dark:bg-gray-800/60">
                            <p class="text-xs text-gray-500 dark:text-gray-400">Validity</p>
                            <p class="text-lg font-bold text-gray-900 dark:text-white" x-text="selectedPlan.validity_days > 0 ? (selectedPlan.validity_days + 'd') : 'No expiry'"></p>
                        </div>
                    </div>
                </template>

                <!-- Note -->
                <div>
                    <label class="block mb-1.5 text-sm font-semibold text-gray-700 dark:text-gray-300">Reason / note <span class="font-normal text-gray-400">(optional)</span></label>
                    <input type="text" name="note" maxlength="255"
                        placeholder="e.g. Manual top-up, paid in cash…"
                        class="w-full px-4 py-2.5 text-sm text-gray-900 bg-gray-50 border border-gray-200 rounded-lg dark:bg-gray-800 dark:border-gray-700 dark:text-white focus:ring-2 focus:ring-[#018DBD] focus:border-[#018DBD] outline-none">
                </div>

                <!-- Live preview -->
                <div class="flex items-center gap-3 p-3.5 rounded-lg bg-[#018DBD]/10 border border-[#018DBD]/20" x-show="selectedPlan">
                    <svg class="w-5 h-5 text-[#018DBD] shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <p class="text-sm text-[#01698c] dark:text-[#13C3C3]">
                        Driver will have
                        <span class="font-bold" x-text="driverRemaining + (selectedPlan ? selectedPlan.rides : 0)"></span>
                        rides remaining (currently <span x-text="driverRemaining"></span>).
                    </p>
                </div>

                <!-- Actions -->
                <div class="flex gap-3 pt-1">
                    <button type="button" @click="planOpen = false"
                        class="flex-1 px-4 py-2.5 text-sm font-semibold text-gray-700 bg-gray-100 rounded-lg dark:bg-gray-800 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-700 transition">
                        Cancel
                    </button>
                    <button type="submit"
                        :disabled="!selectedPlanId"
                        class="flex-1 inline-flex items-center justify-center gap-2 px-4 py-2.5 text-sm font-semibold text-white rounded-lg bg-gradient-to-r from-[#018DBD] to-[#13C3C3] shadow-sm hover:opacity-90 transition disabled:opacity-50 disabled:cursor-not-allowed">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        Assign Plan
                    </button>
                </div>
            </form>
        </template>
    </div>
</div>
