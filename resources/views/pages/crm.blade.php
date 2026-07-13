<div class="container mx-auto p-3">

    <div class="flex justify-between items-center mb-5 p-2">

        <div>
            <h1 class="text-2xl font-bold">CRM Leads</h1>
            <p class="text-zinc-500">Manage leads and sales opportunities</p>
        </div>

        <button id="btnNewLead" class="bg-orange-400 hover:bg-orange-500 text-white px-4 py-2 rounded-lg">
            + New Lead
        </button>

    </div>
    <!-- CRM Status Count Cards -->
    <section class="w-full my-5">
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-7 gap-3">
            <div class="statusBtn max-md:col-span-2 bg-white border border-gray-200 rounded-xl p-4 shadow-sm  cursor-pointer"
                data-status="ALL">

                <div class="w-full flex-1 items-center">
                    <div class="w-full py-1  rounded-full bg-blue-500">
                    </div>
                </div>
                <p class="text-xs text-zinc-400 font-semibold">ALL</p>
                <p class="text-2xl font-bold text-black" id="countALL">0</p>
            </div>

            <div class="statusBtn bg-white border border-gray-200 rounded-xl p-4 shadow-sm  cursor-pointer"
                data-status="LEAD">

                <div class="w-full flex-1 items-center">
                    <div class="w-full py-1  rounded-full bg-gray-500">
                    </div>
                </div>
                <p class="text-xs text-zinc-400 font-semibold">LEAD</p>
                <p class="text-2xl font-bold text-black" id="countLead">0</p>
            </div>

            <div class="statusBtn  bg-white border border-gray-200 rounded-xl p-4 shadow-sm  cursor-pointer"
                data-status="QUALIFIED">
                <div class="w-full flex-1 items-center">
                    <div class="w-full py-1  rounded-full bg-indigo-500">
                    </div>
                </div>
                <p class="text-xs text-zinc-400 font-semibold">QUALIFIED</p>
                <p class="text-2xl font-bold text-black" id="countQualified">0</p>
            </div>

            <div class="statusBtn bg-white border border-gray-200 rounded-xl p-4 shadow-sm  cursor-pointer"
                data-status="OPPORTUNITY">
                <div class="w-full flex-1 items-center">
                    <div class="w-full py-1  rounded-full bg-purple-500">
                    </div>
                </div>
                <p class="text-xs text-zinc-400 font-semibold">OPPORTUNITY</p>
                <p class="text-2xl font-bold text-black" id="countOpportunity">0</p>
            </div>

            <div class="statusBtn bg-white border border-gray-200 rounded-xl p-4 shadow-sm  cursor-pointer"
                data-status="NEGOTIATION">
                <div class="w-full flex-1 items-center">
                    <div class="w-full py-1  rounded-full bg-amber-500">
                    </div>
                </div>
                <p class="text-xs text-zinc-400 font-semibold">NEGOTIATION</p>
                <p class="text-2xl font-bold text-black" id="countNegotiation">0</p>
            </div>

            <div class="statusBtn bg-white border border-gray-200 rounded-xl p-4 shadow-sm  cursor-pointer"
                data-status="WIN">
                <div class="w-full flex-1 items-center">
                    <div class="w-full py-1  rounded-full bg-green-500">
                    </div>
                </div>
                <p class="text-xs text-zinc-400 font-semibold">WIN</p>
                <p class="text-2xl font-bold text-black" id="countWin">0</p>
            </div>

            <div class="statusBtn bg-white border border-gray-200 rounded-xl p-4 shadow-sm  cursor-pointer"
                data-status="LOST">
                <div class="w-full flex-1 items-center">
                    <div class="w-full py-1  rounded-full bg-red-500">
                    </div>
                </div>
                <p class="text-xs text-zinc-400 font-semibold">LOST</p>
                <p class="text-2xl font-bold text-black" id="countLose">0</p>
            </div>

        </div>
    </section>

    <x-table id="tableCrm" />

</div>




<x-side-modal id="LeadDetailsSideModal">

    <div class="p-5 border-b flex justify-between sticky top-0 bg-white dark:bg-zinc-800 z-10">




        <p class="text-xl font-semibold dark:text-white">
            New CRM Lead
        </p>

        <button class="modal-close">
            ✕
        </button>

    </div>

    <div class="p-5">


        <div class="p-5">
            <form id="leadForm">
                <div class="grid grid-cols-2 gap-3">

                    <!-- Contact Name -->
                    <div class="flex flex-col gap-1">
                        <label class="text-[11px] font-medium text-zinc-400 uppercase tracking-widest">Contact
                            Name</label>
                        <input type="text" name="contact_name"
                            class="w-full bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-lg px-3 py-2 text-sm text-zinc-800 dark:text-zinc-100 placeholder-zinc-400 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 transition">
                    </div>

                    <!-- Mobile -->
                    <div class="flex flex-col gap-1">
                        <label class="text-[11px] font-medium text-zinc-400 uppercase tracking-widest">Mobile</label>
                        <input type="text" name="mobile" required
                            class="format-mobile w-full bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-lg px-3 py-2 text-sm text-zinc-800 dark:text-zinc-100 placeholder-zinc-400 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 transition">
                    </div>

                    <!-- Email -->
                    <div class="flex flex-col gap-1 col-span-2">
                        <label class="text-[11px] font-medium text-zinc-400 uppercase tracking-widest">Email</label>
                        <input type="email" name="email" required
                            class="w-full bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-lg px-3 py-2 text-sm text-zinc-800 dark:text-zinc-100 placeholder-zinc-400 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 transition">
                    </div>

                    <!-- Company Name -->
                    <div class="flex flex-col gap-1">
                        <label class="text-[11px] font-medium text-zinc-400 uppercase tracking-widest">Company
                            Name</label>
                        <input type="text" name="company_name" required
                            class="w-full bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-lg px-3 py-2 text-sm text-zinc-800 dark:text-zinc-100 placeholder-zinc-400 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 transition">
                    </div>

                    <!-- Position -->
                    <div class="flex flex-col gap-1">
                        <label class="text-[11px] font-medium text-zinc-400 uppercase tracking-widest">Position /
                            Role</label>
                        <input type="text" name="position" required
                            class="w-full bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-lg px-3 py-2 text-sm text-zinc-800 dark:text-zinc-100 placeholder-zinc-400 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 transition">
                    </div>

                    <!-- Status -->
                    <div class="flex flex-col gap-1">
                        <label class="text-[11px] font-medium text-zinc-400 uppercase tracking-widest">Status</label>
                        <select name="status" required
                            class="statusDropDown w-full bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-lg px-3 py-2 text-sm text-zinc-800 dark:text-zinc-100 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 transition">
                        </select>
                    </div>

                    <!-- Source -->
                    <div class="flex flex-col gap-1">
                        <label class="text-[11px] font-medium text-zinc-400 uppercase tracking-widest">Source</label>
                        <input type="text" name="source" required
                            class="w-full bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-lg px-3 py-2 text-sm text-zinc-800 dark:text-zinc-100 placeholder-zinc-400 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 transition">
                    </div>

                    <!-- Estimated Value -->
                    <div class="flex flex-col gap-1 col-span-2">
                        <label class="text-[11px] font-medium text-zinc-400 uppercase tracking-widest">Estimated
                            Value</label>
                        <input type="text" name="est_value"
                            class="format-currency w-full bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-lg px-3 py-2 text-sm text-zinc-800 dark:text-zinc-100 placeholder-zinc-400 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 transition">
                    </div>

                    <!-- Notes -->
                    <div class="flex flex-col gap-1 col-span-2">
                        <label class="text-[11px] font-medium text-zinc-400 uppercase tracking-widest">Notes</label>
                        <textarea name="notes" id="notes" rows="5"
                            class="w-full bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-lg px-3 py-2 text-sm text-zinc-800 dark:text-zinc-100 placeholder-zinc-400 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 transition resize-none"></textarea>
                    </div>

                </div>
            </form>
        </div>

        <!-- Footer -->
        <div class="border-t border-zinc-100 dark:border-zinc-800 px-5 py-4 flex justify-end gap-2">
            <button type="button"
                class="modal-close px-4 py-1.5 text-sm font-medium text-zinc-600 dark:text-zinc-300 bg-zinc-100 dark:bg-zinc-800 hover:bg-zinc-200 dark:hover:bg-zinc-700 rounded-lg transition">
                Cancel
            </button>
            <button type="submit" id="saveLeadBtn"
                class="px-4 py-1.5 text-sm font-medium text-white bg-orange-500 hover:bg-orange-600 rounded-lg transition">
                Save Lead
            </button>
        </div>
    </div>

</x-side-modal>
<x-modal id="LeadInfoModal">

    {{-- Header --}}
    <div class="p-5 border-b flex justify-between items-center">
        <div class="flex flex-col gap-1">
            <div class="flex items-center gap-2">
                <p class="text-lg font-semibold" id="leadCompanyName">Company Name</p>
                <div id="leadStatus"></div>
            </div>
            <p class="text-xs text-zinc-400">
                Lead created <span id="leadCreatedAt">-</span>
            </p>
        </div>
        <div class="flex items-center gap-2">
            <button id="createClientMasterBtn" class="p-2 bg-orange-600 rounded-lg text-white text-sm hidden">
                <b class="font-black">+</b> Record
            </button>
            <button
                class="modal-close text-zinc-400 hover:text-zinc-600 w-8 h-8 flex items-center justify-center rounded-lg hover:bg-zinc-100 dark:hover:bg-zinc-800">
                ✕
            </button>
        </div>
    </div>

    <div class="max-h-[75vh] overflow-auto p-5 space-y-5">

        {{-- ============== LEAD & COMPANY INFORMATION ============== --}}
        <div class="relative border border-zinc-200 dark:border-zinc-700 rounded-xl p-4">

            <div class="flex justify-between items-center mb-3">
                <p class="text-xs font-semibold text-zinc-400 uppercase tracking-widest">Lead & Company Information</p>
                <button id="editContactBtn"
                    class="text-zinc-400 hover:text-zinc-600 p-1 rounded-md hover:bg-zinc-100 dark:hover:bg-zinc-800">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round">
                        <path d="M12 20h9"></path>
                        <path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L7 19l-4 1 1-4 12.5-12.5z"></path>
                    </svg>
                </button>
            </div>

            {{-- Edit contact info dropdown --}}
            <div id="editContactInfoDropdown"
                class="modaldropdown hidden absolute right-4 top-12 w-80 bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-700 rounded-xl p-4 z-50 shadow-xl shadow-black/10 dark:shadow-black/40">

                <p class="text-xs font-medium text-zinc-400 uppercase tracking-widest mb-4">Edit Contact Information
                </p>

                <div class="flex flex-col gap-3">
                    <div class="flex flex-col gap-1">
                        <label for="contactName"
                            class="text-[11px] font-medium text-zinc-400 uppercase tracking-widest">Contact
                            Name</label>
                        <input type="text" name="contactName" id="contactName"
                            class="w-full bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-lg px-3 py-2 text-sm text-zinc-800 dark:text-zinc-100 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 transition">
                    </div>
                    <div class="flex flex-col gap-1">
                        <label for="contactEmail"
                            class="text-[11px] font-medium text-zinc-400 uppercase tracking-widest">Contact
                            Email</label>
                        <input type="email" name="contactEmail" id="contactEmail"
                            class="w-full bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-lg px-3 py-2 text-sm text-zinc-800 dark:text-zinc-100 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 transition">
                    </div>
                    <div class="flex flex-col gap-1">
                        <label for="contactMobile"
                            class="text-[11px] font-medium text-zinc-400 uppercase tracking-widest">Contact
                            Mobile</label>
                        <input type="text" name="contactMobile" id="contactMobile"
                            class="w-full bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-lg px-3 py-2 text-sm text-zinc-800 dark:text-zinc-100 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 transition">
                    </div>
                </div>

                <div class="flex justify-end gap-2 mt-4 pt-4 border-t border-zinc-100 dark:border-zinc-800">
                    <button id="cancelContactInfoBtn"
                        class="px-4 py-1.5 text-sm font-medium text-zinc-600 dark:text-zinc-300 bg-zinc-100 dark:bg-zinc-800 hover:bg-zinc-200 dark:hover:bg-zinc-700 rounded-lg transition">
                        Cancel
                    </button>
                    <button id="saveContactInfoBtn"
                        class="px-4 py-1.5 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-lg transition">
                        Save
                    </button>
                </div>
            </div>

            {{-- Contact & Company field grid --}}
            <div class="grid grid-cols-2 md:grid-cols-3 gap-x-4 gap-y-3 text-sm">
                <div>
                    <p class="text-[11px] font-medium text-zinc-400 uppercase tracking-widest">Contact Name</p>
                    <p class="font-medium text-zinc-800 dark:text-zinc-100" id="leadContactName">-</p>
                </div>
                <div>
                    <p class="text-[11px] font-medium text-zinc-400 uppercase tracking-widest">Position</p>
                    <p class="font-medium text-zinc-800 dark:text-zinc-100" id="leadPosition">-</p>
                </div>
                <div>
                    <p class="text-[11px] font-medium text-zinc-400 uppercase tracking-widest">Email</p>
                    <p class="font-medium text-zinc-800 dark:text-zinc-100" id="leadEmail">-</p>
                </div>
                <div>
                    <p class="text-[11px] font-medium text-zinc-400 uppercase tracking-widest">Mobile</p>
                    <p class="font-medium text-zinc-800 dark:text-zinc-100" id="leadMobile">-</p>
                </div>
                <div>
                    <p class="text-[11px] font-medium text-zinc-400 uppercase tracking-widest">Source</p>
                    <p class="font-medium text-zinc-800 dark:text-zinc-100" id="leadSource">-</p>
                </div>
                <div>
                    <p class="text-[11px] font-medium text-zinc-400 uppercase tracking-widest">Assigned To</p>
                    <p class="font-medium text-zinc-800 dark:text-zinc-100" id="leadAssignedTo">-</p>
                </div>

                <div class="col-span-2 md:col-span-3 border-t border-zinc-100 dark:border-zinc-800 pt-3">
                    <p class="text-[11px] font-medium text-zinc-400 uppercase tracking-widest">Company Name</p>
                    <p class="font-medium text-zinc-800 dark:text-zinc-100" id="leadCompanyNameFull">-</p>
                </div>
                <div class="col-span-2 md:col-span-2">
                    <p class="text-[11px] font-medium text-zinc-400 uppercase tracking-widest">Company Address</p>
                    <p class="font-medium text-zinc-800 dark:text-zinc-100" id="leadCompanyAddress">-</p>
                </div>
                <div>
                    <p class="text-[11px] font-medium text-zinc-400 uppercase tracking-widest">Type of Business</p>
                    <p class="font-medium text-zinc-800 dark:text-zinc-100" id="leadTypeOfBusiness">-</p>
                </div>

                <div>
                    <p class="text-[11px] font-medium text-zinc-400 uppercase tracking-widest">Estimated Value</p>
                    <p class="font-medium text-zinc-800 dark:text-zinc-100" id="leadEstimatedValue">-</p>
                </div>
                <div>
                    <p class="text-[11px] font-medium text-zinc-400 uppercase tracking-widest">Expected Close Date</p>
                    <p class="font-medium text-zinc-800 dark:text-zinc-100" id="leadExpectedCloseDate">-</p>
                </div>
            </div>
        </div>

        {{-- ============== CONTAINER REQUIREMENTS ============== --}}
        <div class="border border-zinc-200 dark:border-zinc-700 rounded-xl p-4">
            <p class="text-xs font-semibold text-zinc-400 uppercase tracking-widest mb-3">Container Requirements</p>
            <div id="containerListContainer"
                class="grid grid-cols-1 md:grid-cols-2 gap-2 max-h-64 overflow-auto p-0.5">
            </div>
        </div>

        {{-- ============== PROPOSALS + COMPACT HISTORY ============== --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">

            {{-- Proposals (wider) --}}
            <div class="lg:col-span-2 border border-zinc-200 dark:border-zinc-700 rounded-xl p-4">
                <p class="text-xs font-semibold text-zinc-400 uppercase tracking-widest mb-3">Proposals</p>
                <div id="proposalContainer"
                    class="border border-zinc-300 dark:border-zinc-700 rounded-lg flex flex-col max-h-[24vh] overflow-auto p-1 gap-1">
                </div>
            </div>

            {{-- Compact history sidebar: Activities + Notes --}}
            <div class="lg:col-span-1 flex flex-col gap-4">

                {{-- Activities (compact) --}}
                <div class="border border-zinc-200 dark:border-zinc-700 rounded-xl p-3 relative">
                    <div class="flex justify-between items-center mb-2">
                        <p class="text-[11px] font-semibold text-zinc-400 uppercase tracking-widest">Activity</p>
                        <button id="addActivityBtn"
                            class="w-6 h-6 flex items-center justify-center rounded-md bg-zinc-100 dark:bg-zinc-800 hover:bg-zinc-200 dark:hover:bg-zinc-700 text-sm font-semibold text-zinc-600 dark:text-zinc-300">
                            +
                        </button>
                    </div>

                    <div id="activityDropdown"
                        class="modaldropdown hidden absolute right-2 top-9 w-72 bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-700 rounded-xl p-4 z-50 flex flex-col gap-3 shadow-xl shadow-black/10 dark:shadow-black/40">

                        <p class="text-xs font-medium text-zinc-400 uppercase tracking-widest">Add New Activity</p>

                        <div class="grid grid-cols-2 gap-3">
                            <div class="flex flex-col gap-1">
                                <label for="activityStatusInput"
                                    class="text-[11px] font-medium text-zinc-400 uppercase tracking-widest">Status</label>
                                <select name="status" id="activityStatusInput" required
                                    class="statusDropDown w-full bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-lg px-2 py-1.5 text-sm text-zinc-800 dark:text-zinc-100 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 transition">
                                    <option value="">Select status</option>
                                </select>
                            </div>
                            <div class="flex flex-col gap-1">
                                <label for="activityTypeInput"
                                    class="text-[11px] font-medium text-zinc-400 uppercase tracking-widest">Activity</label>
                                <input type="text" name="type" id="activityTypeInput"
                                    class="w-full bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-lg px-2 py-1.5 text-sm text-zinc-800 dark:text-zinc-100 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 transition">
                            </div>
                        </div>

                        <div class="flex flex-col gap-1">
                            <label for="activityDescriptionInput"
                                class="text-[11px] font-medium text-zinc-400 uppercase tracking-widest">Description</label>
                            <textarea name="activityDescriptionInput" id="activityDescriptionInput" rows="3" placeholder="Add activity..."
                                class="w-full bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-lg px-2 py-1.5 text-sm text-zinc-800 dark:text-zinc-100 placeholder-zinc-400 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 transition resize-none"></textarea>
                        </div>

                        <div class="flex flex-col gap-1">
                            <label for="activityAttachmentInput"
                                class="text-[11px] font-medium text-zinc-400 uppercase tracking-widest">
                                Attachment
                            </label>
                            <input type="file" name="attachment" id="activityAttachmentInput"
                                class="block w-full text-xs text-zinc-700 dark:text-zinc-200
               file:mr-2 file:py-1.5 file:px-3
               file:rounded-lg file:border-0
               file:text-xs file:font-medium
               file:bg-blue-50 file:text-blue-700
               hover:file:bg-blue-100
               dark:file:bg-blue-900/40 dark:file:text-blue-300
               cursor-pointer
               bg-zinc-50 dark:bg-zinc-800
               border border-zinc-200 dark:border-zinc-700
               rounded-lg p-1.5
               focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 transition">
                        </div>

                        <div class="flex justify-end gap-2 pt-3 border-t border-zinc-100 dark:border-zinc-800">
                            <button id="cancelActivityBtn"
                                class="px-3 py-1.5 text-xs font-medium text-zinc-600 dark:text-zinc-300 bg-zinc-100 dark:bg-zinc-800 hover:bg-zinc-200 dark:hover:bg-zinc-700 rounded-lg transition">
                                Cancel
                            </button>
                            <button id="saveActivityBtn"
                                class="px-3 py-1.5 text-xs font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-lg transition">
                                Save
                            </button>
                        </div>
                    </div>

                    <div class="flex flex-col gap-1.5 max-h-40 overflow-auto pr-0.5" id="activityContainer">
                        <div class="w-full p-2 rounded-md text-center">
                            <p class="text-xs font-semibold text-zinc-400">No activities found</p>
                        </div>
                    </div>
                </div>

                {{-- Notes (compact) --}}
                <div class="border border-zinc-200 dark:border-zinc-700 rounded-xl p-3 relative">
                    <div class="flex justify-between items-center mb-2">
                        <p class="text-[11px] font-semibold text-zinc-400 uppercase tracking-widest">Notes</p>
                        <button id="addNoteBtn"
                            class="w-6 h-6 flex items-center justify-center rounded-md bg-zinc-100 dark:bg-zinc-800 hover:bg-zinc-200 dark:hover:bg-zinc-700 text-sm font-semibold text-zinc-600 dark:text-zinc-300">
                            +
                        </button>
                    </div>

                    <div id="noteDropdown"
                        class="modaldropdown hidden absolute right-2 top-9 w-72 bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-700 rounded-xl p-4 z-50 flex flex-col gap-3 shadow-xl shadow-black/10 dark:shadow-black/40">

                        <p class="text-xs font-medium text-zinc-400 uppercase tracking-widest">Add New Note</p>

                        <div class="flex flex-col gap-1">
                            <label for="noteInput"
                                class="text-[11px] font-medium text-zinc-400 uppercase tracking-widest">Note</label>
                            <textarea name="noteInput" id="noteInput" rows="3" placeholder="Add note..."
                                class="w-full bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-lg px-2 py-1.5 text-sm text-zinc-800 dark:text-zinc-100 placeholder-zinc-400 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 transition resize-none"></textarea>
                        </div>

                        <div class="flex justify-end gap-2 pt-3 border-t border-zinc-100 dark:border-zinc-800">
                            <button id="cancelNoteBtn"
                                class="px-3 py-1.5 text-xs font-medium text-zinc-600 dark:text-zinc-300 bg-zinc-100 dark:bg-zinc-800 hover:bg-zinc-200 dark:hover:bg-zinc-700 rounded-lg transition">
                                Cancel
                            </button>
                            <button id="saveNoteBtn"
                                class="px-3 py-1.5 text-xs font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-lg transition">
                                Save
                            </button>
                        </div>
                    </div>

                    <div class="flex flex-col gap-1.5 max-h-40 overflow-auto pr-0.5" id="noteContainer">
                        <div class="w-full p-2 rounded-md text-center">
                            <p class="text-xs font-semibold text-zinc-400">No notes found</p>
                        </div>
                    </div>
                </div>

            </div>
        </div>

    </div>

    <div class="border-t px-5 py-4 flex justify-end gap-2">
        <button class="modal-close border px-4 py-2 rounded-lg">
            Close
        </button>
    </div>

</x-modal>


<x-new-proposal-modal />

<script>
    (function() {
        initCrmLogic();
    })();
</script>
