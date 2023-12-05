<x-filament-panels::page>

    <div class="flex md:flex md:flex-grow flex-row justify-end space-x-1">
        <a class="py-4 px-2 text-teal-500 border-b-4 border-teal-300 font-semibold" href="{{route('filament.admin.resources.members.view', $this->user->id)}}">View Member Profile</a>
        <a class="py-4 px-2 text-teal-500 border-b-4 border-teal-300 font-semibold" href="{{route('filament.admin.resources.members.edit', $this->user->id)}}">Edit Member Profile</a>
        <a class="py-4 px-2 text-teal-500 border-b-4 border-teal-300 font-semibold" href="{{route('filament.admin.resources.members.list-membership-plans', $this->user->id)}}">List Memberships</a>
    </div 
{{ $this->memberInfo }}

{{ $this->table }}
</x-filament-panels::page>
