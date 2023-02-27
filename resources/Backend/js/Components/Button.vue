<template>
  <button
    v-if="href === null"
    :type="type"
    :disabled="loading || disabled"
    class="inline-flex items-center justify-center space-x-2 font-semibold tracking-widest uppercase transition rounded cursor-pointer disabled:opacity-25 focus:outline-none"
    :class="buttonClass"
  >
    <i class="gg-spinner" v-if="loading"></i>
    <template v-if="label">
      <span>{{ label }} </span>
    </template>
    <div v-else class="flex items-center space-x-2">
      <slot></slot>
    </div>
  </button>
  <Link
    v-else
    :href="href"
    :disabled="loading || disabled"
    class="inline-flex items-center justify-center space-x-2 font-semibold tracking-widest uppercase transition rounded cursor-pointer disabled:opacity-25 focus:outline-none"
    :class="buttonClass"
  >
    <template v-if="label">
      <span>{{ label }} </span>
    </template>
    <div v-else class="flex items-center space-x-2">
      <slot></slot>
    </div>
  </Link>
</template>

<script>
export default {
  props: {
    type: {
      type: String,
      default: 'button',
    },
    variant: {
      type: String,
      default: 'primary',
    },
    size: {
      type: String,
      default: 'md',
    },
    href: {
      type: String,
      default: null,
      requried: false,
    },
    label: {
      type: String,
      default: '',
    },
    loading: {
      type: Boolean,
      default: false,
    },
    disabled: {
      type: Boolean,
      default: false,
    },
  },
  computed: {
    buttonClass() {
      const variant = {
        primary: 'text-white bg-primary hover:bg-primary-darker active:bg-primary-600',
        'outline-primary': 'bg-transparent text-primary border border-primary hover:text-white hover:bg-primary',
        'outline-secondary':
          'bg-transparent text-gray-700 border border-gray-300 hover:border-gray-400 hover:text-gray-900 hover:bg-gray-200',
        white:
          'bg-white text-gray-700 border border-gray-300 hover:border-gray-400 hover:text-gray-900 hover:bg-gray-200',
        ghost: 'text-gray-700 hover:text-gray-900',
        link: 'bg-transparent text-blue-600 hover:underline px-0 py-0',

        success: 'text-green-600 bg-green-300 hover:bg-green-400 hover:text-green-700',

        'link-danger': 'bg-transparent text-red-600 hover:underline px-0 py-0',
      }[this.variant];

      const size = {
        xs: 'text-xs h-[1.4rem] px-2',
        sm: 'text-xs h-[1.75rem] px-3',
        md: 'text-sm h-[2.625rem] px-6',
        lg: 'text-sm h-[4rem] px-6',
      }[this.size];

      return ` ${variant} ${size}`;
    },
  },
};
</script>
