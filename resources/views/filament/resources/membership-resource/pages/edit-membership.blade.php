 <x-filament::page>

     {{ $this->memberInfo }}
     <form wire:submit="save" id="form" class="grid gap-y-6">
         {{ $this->form }}

         <div style="padding: 2rem;">
             <x-filament::actions :actions="$this->getFormActions()" alignment="center" />
         </div>

     </form>
 </x-filament::page>