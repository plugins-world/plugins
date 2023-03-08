@if(session('tips'))
<!-- Global notification live region, render this permanently at the end of the document -->
<div aria-live="assertive" class="pointer-events-none fixed inset-0 flex items-center md:items-start px-4 py-6 sm:items-center sm:p-6" style="z-index:9999;" x-data="tips()" x-show="isOpen()" x-on:click.outside="close()" x-init="init()" x-clock>
  <div class="flex w-full flex-col items-center space-y-4 sm:items-end">
    <!--
      Notification panel, dynamically insert this into the live region when it needs to be displayed

      Entering: "transform ease-out duration-300 transition"
        From: "translate-y-2 opacity-0 sm:translate-y-0 sm:translate-x-2"
        To: "translate-y-0 opacity-100 sm:translate-x-0"
      Leaving: "transition ease-in duration-100"
        From: "opacity-100"
        To: "opacity-0"
    -->
    <div class="pointer-events-auto w-full max-w-sm overflow-hidden rounded-lg bg-white shadow-lg ring-1 ring-black ring-opacity-5" x-transition:enter="transform ease-out duration-300 transition" x-transition:enter-start="translate-y-2 opacity-0 sm:translate-y-0 sm:translate-x-2" x-transition:enter-end="translate-y-0 opacity-100 sm:translate-x-0" x-transition:leave="transition ease-in duration-100" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
      <div class="p-4">
        <div class="flex items-start">
          <div class="flex-shrink-0">
            @if(session('tips_type', 'success') == 'success')
            <svg class="h-6 w-6 text-green-400" fill="currentColor" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
              <use xlink:href="#icon-chenggong"></use>
            </svg>
            @else
            <svg class="h-6 w-6 text-red-400" fill="currentColor" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
              <use xlink:href="#icon-shibai"></use>
            </svg>
            @endif
          </div>
          <div class="ml-3 w-0 flex-1 pt-0.5">
            @if(session('tips_type', 'success') == 'success')
            <p class="text-sm font-medium text-gray-900">操作成功!</p>
            <p class="mt-1 text-sm text-gray-500">{{ session('tips') }}</p>
            @else
            <p class="text-sm font-medium text-red-900">操作失败!</p>
            <p class="mt-1 text-sm text-red-500">{{ session('tips') }}</p>
            @endif
          </div>


          <div class="ml-4 flex flex-shrink-0">
            <button x-on:click="close()" type="button" class="inline-flex rounded-md bg-white text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
              <span class="sr-only">关闭</span>
              <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                <path d="M6.28 5.22a.75.75 0 00-1.06 1.06L8.94 10l-3.72 3.72a.75.75 0 101.06 1.06L10 11.06l3.72 3.72a.75.75 0 101.06-1.06L11.06 10l3.72-3.72a.75.75 0 00-1.06-1.06L10 8.94 6.28 5.22z" />
              </svg>
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
  function tips() {
    return {
      open: true,
      init() {
        setTimeout(() => {
          this.open = false
        }, 3000)
      },
      isOpen() {
        return this.open
      },
      close() {
        this.open = false
      }
    }
  }
</script>
@endif