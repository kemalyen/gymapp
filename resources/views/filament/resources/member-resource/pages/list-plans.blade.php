<x-filament-panels::page>

    <div class="flex md:flex md:flex-grow flex-row justify-end gap-3">
        <a style="--c-400:var(--primary-400);--c-500:var(--primary-500);--c-600:var(--primary-600);" class="fi-btn relative grid-flow-col items-center justify-center font-semibold outline-none transition duration-75 focus-visible:ring-2 rounded-lg fi-color-custom fi-btn-color-primary fi-size-md fi-btn-size-md gap-1.5 px-3 py-2 text-sm inline-grid shadow-sm bg-custom-600 text-white hover:bg-custom-500 dark:bg-custom-500 dark:hover:bg-custom-400 focus-visible:ring-custom-500/50 dark:focus-visible:ring-custom-400/50 fi-ac-btn-action " href="{{route('filament.admin.resources.members.view', $this->user->id)}}"><span class="fi-btn-label">View Member Profile</span></a>
        <a style="--c-400:var(--primary-400);--c-500:var(--primary-500);--c-600:var(--primary-600);" class="fi-btn relative grid-flow-col items-center justify-center font-semibold outline-none transition duration-75 focus-visible:ring-2 rounded-lg fi-color-custom fi-btn-color-primary fi-size-md fi-btn-size-md gap-1.5 px-3 py-2 text-sm inline-grid shadow-sm bg-custom-600 text-white hover:bg-custom-500 dark:bg-custom-500 dark:hover:bg-custom-400 focus-visible:ring-custom-500/50 dark:focus-visible:ring-custom-400/50 fi-ac-btn-action " href="{{route('filament.admin.resources.members.edit', $this->user->id)}}"><span class="fi-btn-label">Edit Member Profile</span></a>
        <a style="--c-400:var(--primary-400);--c-500:var(--primary-500);--c-600:var(--primary-600);" class="fi-btn relative grid-flow-col items-center justify-center font-semibold outline-none transition duration-75 focus-visible:ring-2 rounded-lg fi-color-custom fi-btn-color-primary fi-size-md fi-btn-size-md gap-1.5 px-3 py-2 text-sm inline-grid shadow-sm bg-custom-600 text-white hover:bg-custom-500 dark:bg-custom-500 dark:hover:bg-custom-400 focus-visible:ring-custom-500/50 dark:focus-visible:ring-custom-400/50 fi-ac-btn-action " href="{{route('filament.admin.resources.members.attendances', $this->user->id)}}"><span class="fi-btn-label">Attendece Report</span></a>
    </div 
    
    {{ $this->memberInfo }}
    
    {{ $this->table }}
     
</x-filament-panels::page>
