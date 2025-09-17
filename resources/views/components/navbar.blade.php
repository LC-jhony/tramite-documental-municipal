      <div class="flex items-center space-x-1">
          {{ $slot }}
      </div>

      <!-- Mobile menu button -->
      <div class="sm:hidden">
          <button @click="open = !open"
              class="inline-flex items-center justify-center rounded-md p-2 text-gray-400 hover:text-white hover:bg-gray-800 focus:outline-none">
              <svg x-show="!open" class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round"
                      d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
              </svg>
              <svg x-show="open" class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="1.5"
                  viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
              </svg>
          </button>
      </div>
      <!-- Mobile menu -->
      <div x-show="open" x-transition class="sm:hidden px-2 pt-2 pb-3 space-y-1">
          <a href="#"
              class="block rounded-md px-3 py-2 text-base font-medium text-white bg-gray-800">Dashboard</a>
          <a href="#"
              class="block rounded-md px-3 py-2 text-base font-medium text-gray-300 hover:bg-gray-700 hover:text-white">Team</a>
          <a href="#"
              class="block rounded-md px-3 py-2 text-base font-medium text-gray-300 hover:bg-gray-700 hover:text-white">Projects</a>
          <a href="#"
              class="block rounded-md px-3 py-2 text-base font-medium text-gray-300 hover:bg-gray-700 hover:text-white">Calendar</a>
      </div>
