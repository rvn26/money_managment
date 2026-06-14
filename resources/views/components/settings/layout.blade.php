<div class="flex items-start max-md:flex-col">
    <div class="me-10 w-full pb-4 md:w-[220px]">
        <div class="bg-white dark:bg-zinc-800 p-5 shadow-md rounded-xl">
            <flux:navlist aria-label="{{ __('Settings') }}">
                <flux:navlist.item :href="route('profile.edit')" wire:navigate>{{ __('Profile') }}</flux:navlist.item>
                <flux:navlist.item :href="route('user-password.edit')" wire:navigate>{{ __('Password') }}
                </flux:navlist.item>
                @if (Laravel\Fortify\Features::canManageTwoFactorAuthentication())
                    <flux:navlist.item :href="route('two-factor.show')" wire:navigate>{{ __('Two-Factor Auth') }}
                    </flux:navlist.item>
                @endif
                <flux:navlist.item :href="route('appearance.edit')" wire:navigate>{{ __('Appearance') }}
                </flux:navlist.item>
            </flux:navlist>
        </div>
    </div>

    <flux:separator class="md:hidden" />

    <div class="flex-1 self-stretch max-md:pt-6 bg-white shadow-md rounded-xl p-6 dark:bg-zinc-800">
        <flux:heading>{{ $heading ?? '' }}</flux:heading>
        <flux:subheading>{{ $subheading ?? '' }}</flux:subheading>

        <div class="mt-5 w-full max-w-lg ">
            {{ $slot }}
        </div>
    </div>
</div>