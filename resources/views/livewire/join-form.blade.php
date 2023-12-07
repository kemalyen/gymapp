<div>
    <form wire:submit="create">
        {{ $this->form }}

        <button type="submit"
            class="fi-btn bg-blue-500  relative grid-flow-col items-center justify-center font-semibold outline-none transition duration-75 focus-visible:ring-2 rounded-lg fi-color-custom fi-btn-color-primary fi-size-md fi-btn-size-md gap-1.5 px-3 py-2 text-sm inline-grid shadow-sm bg-custom-600 text-white hover:bg-custom-500 dark:bg-custom-500 dark:hover:bg-custom-400 focus-visible:ring-custom-500/50 dark:focus-visible:ring-custom-400/50">
            Submit
        </button>
    </form>

    <x-filament-actions::modals />
</div>
