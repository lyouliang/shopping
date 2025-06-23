<template>
  <div :class="badgeClasses">
    <slot />
  </div>
</template>

<script setup lang="ts">
import { computed } from "vue";
import { badgeVariants } from ".";
import { cn } from "@/lib/utils";

const props = withDefaults(
  defineProps<{
    variant?: "default" | "secondary" | "destructive" | "outline";
    class?: string;
  }>(),
  {
    variant: "default",
    class: "",
  }
);

const badgeClasses = computed(() => {
  return cn(badgeVariants({ variant: props.variant }), props.class);
});
</script>