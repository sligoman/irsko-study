<div>

    <div class="z-50 fixed bottom-4 right-4">
        <button
            class="z-50 bg-accent-900 text-white rounded-full w-16 h-16 flex items-center justify-center shadow-lg hover:bg-accent-500 focus:outline-hidden focus:shadow-outline"
            @click="$store.init.showLeadForm = true;gtag('event','form_open');"
        >
            <svg class="w-8 h-8" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M16.5 14.5C16.5 14.5 17.5 15.5 17.5 16.5C17.5 17.6 16.6 18.5 15.5 18.5C14.4 18.5 13.5 17.6 13.5 16.5C13.5 15.4 14.4 14.5 15.5 14.5C15.8 14.5 16.2 14.5 16.5 14.5ZM12.5 2C7.8 2 4 5.8 4 10.5C4 15.2 7.8 19 12.5 19C17.2 19 21 15.2 21 10.5C21 5.8 17.2 2 12.5 2ZM12.5 17C9.5 17 7 14.5 7 11.5C7 8.5 9.5 6 12.5 6C15.5 6 18 8.5 18 11.5C18 14.5 15.5 17 12.5 17Z" fill="currentColor"/>
            </svg>

        </button>
    </div>

    <template x-if="$store.init.selectedCourses.length > 0">
    <div class="z-50 fixed bottom-14 right-14">
        <span x-text="$store.init.selectedCourses.length" class="bg-accent-900 text-white rounded-full w-8 h-8 flex items-center justify-center shadow-lg"></span>
    </div>
    </template>

    <div x-show="$store.init.showLeadForm" class="fixed inset-0 z-50 flex items-center justify-center">
        <div class="fixed inset-0 bg-gray-900 opacity-75"></div>
        <div class="bg-white rounded-lg shadow-lg p-6 w-full max-w-md z-0" @click.away="$store.init.showLeadForm = false">

            <button class="absolute top-0 right-0 mt-4 mr-4 text-gray-500 hover:text-gray-800 focus:outline-hidden" @click="$store.init.showLeadForm = false">
                <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M18 6L6 18" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M6 6L18 18" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </button>

            <div>

                <x-lead position="floating"></x-lead>

            </div>

        </div>

    </div>

</div>