<script setup lang="ts">
import {Avatar, AvatarImage, AvatarFallback} from "@/Components/ui/avatar";
import {
  DropdownMenu,
  DropdownMenuContent,
  DropdownMenuItem,
  DropdownMenuLabel,
  DropdownMenuSeparator,
  DropdownMenuTrigger,
} from '@/Components/ui/dropdown-menu'
import {Link} from '@inertiajs/vue3'
import {Button} from '@/Components/ui/button'
</script>

<template>
  <DropdownMenu>

    <DropdownMenuTrigger as-child>
      <Button
        variant="ghost"
        class="flex text-left justify-start items-center gap-2 pl-0 pr-2 w-full">
        <Avatar>
          <AvatarImage
            :src="$page.props.auth.avatar"
            :alt="`${$page.props.auth.user.first_name} ${$page.props.auth.user.last_name}`"
          />
          <AvatarFallback>CN</AvatarFallback>
        </Avatar>

        <span>
           {{ `${$page.props.auth.user.first_name} ${$page.props.auth.user.last_name}` }}
         </span>
      </Button>
    </DropdownMenuTrigger>

    <DropdownMenuContent align="start">
      <DropdownMenuLabel class="grid">
        <strong class="text-xl font-medium">
          {{ `${$page.props.auth.user.first_name} ${$page.props.auth.user.last_name}` }}
        </strong>

        <span class="text-sm text-muted-foreground">
          {{ $page.props.auth.user?.email }}
        </span>
      </DropdownMenuLabel>

      <DropdownMenuSeparator/>

      <DropdownMenuItem
        as-child :href="route('profile.edit')"
      >
        <Link as="button" class="w-full">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" fill="none">
            <path
              d="M6.57757 15.4816C5.1628 16.324 1.45336 18.0441 3.71266 20.1966C4.81631 21.248 6.04549 22 7.59087 22H16.4091C17.9545 22 19.1837 21.248 20.2873 20.1966C22.5466 18.0441 18.8372 16.324 17.4224 15.4816C14.1048 13.5061 9.89519 13.5061 6.57757 15.4816Z"
              stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"
            />
            <path
              d="M16.5 6.5C16.5 8.98528 14.4853 11 12 11C9.51472 11 7.5 8.98528 7.5 6.5C7.5 4.01472 9.51472 2 12 2C14.4853 2 16.5 4.01472 16.5 6.5Z"
              stroke="currentColor" stroke-width="1.5"
            />
          </svg>

          Profile
        </Link>
      </DropdownMenuItem>

      <DropdownMenuItem>
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" fill="none">
          <path
            d="M20 10C19.9641 7.52043 19.7801 6.11466 18.8365 5.17157C17.6643 4 15.7776 4 12.0043 4C8.23106 4 6.34442 4 5.17221 5.17157C4 6.34315 4 8.22876 4 12C4 15.7712 4 17.6569 5.17221 18.8284C6.23545 19.8911 7.88646 19.9899 11 19.9991"
            stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"
          />
          <path
            d="M18 19.7143V21M18 19.7143C16.8432 19.7143 15.8241 19.1461 15.2263 18.2833M18 19.7143C19.1568 19.7143 20.1759 19.1461 20.7737 18.2833M18 13.2857C19.1569 13.2857 20.1761 13.854 20.7738 14.7169M18 13.2857C16.8431 13.2857 15.8239 13.854 15.2262 14.7169M18 13.2857V12M22 13.9286L20.7738 14.7169M14.0004 19.0714L15.2263 18.2833M14 13.9286L15.2262 14.7169M21.9996 19.0714L20.7737 18.2833M20.7738 14.7169C21.1273 15.2271 21.3333 15.8403 21.3333 16.5C21.3333 17.1597 21.1272 17.773 20.7737 18.2833M15.2262 14.7169C14.8727 15.2271 14.6667 15.8403 14.6667 16.5C14.6667 17.1597 14.8728 17.773 15.2263 18.2833"
            stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
          />
          <path d="M9.5 2V4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
          <path d="M14.5 2V4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
          <path d="M9.5 20V22" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
          <path d="M4 9.5L2 9.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                stroke-linejoin="round"
          />
          <path d="M4 14.5L2 14.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                stroke-linejoin="round"
          />
          <path d="M22 9.5L20 9.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                stroke-linejoin="round"
          />
          <path d="M12 8H8" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>

        Settings
      </DropdownMenuItem>

      <DropdownMenuSeparator/>

      <DropdownMenuItem
        as-child method="post"
        :href="route('logout')"
      >
        <Link as="button" class="w-full">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" fill="none">
            <path
              d="M15 17.625C14.9264 19.4769 13.3831 21.0494 11.3156 20.9988C10.8346 20.987 10.2401 20.8194 9.05112 20.484C6.18961 19.6768 3.70555 18.3203 3.10956 15.2815C3 14.723 3 14.0944 3 12.8373L3 11.1627C3 9.90561 3 9.27705 3.10956 8.71846C3.70555 5.67965 6.18961 4.32316 9.05112 3.51603C10.2401 3.18064 10.8346 3.01295 11.3156 3.00119C13.3831 2.95061 14.9264 4.52307 15 6.37501"
              stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
            />
            <path d="M21 12H10M21 12C21 11.2998 19.0057 9.99153 18.5 9.5M21 12C21 12.7002 19.0057 14.0085 18.5 14.5"
                  stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"
            />
          </svg>

          Logout
        </Link>
      </DropdownMenuItem>

    </DropdownMenuContent>

  </DropdownMenu>
</template>

<style scoped>

</style>
