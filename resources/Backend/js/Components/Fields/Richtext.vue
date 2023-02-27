<template>
  <Editor
    api-key="7w40acxv5in6m21n1k9ny0tqej2d44gwyj6557igizadb1r6"
    :init="{ ...initConfig, height: size }"
    :modelValue="value"
    @update:modelValue="$emit('change', $event)"
  />
</template>

<script>
import Editor from '@tinymce/tinymce-vue';

const MAX_FILE_SIZE = 5;

export default {
  components: {
    Editor,
  },
  props: {
    value: {
      type: String,
    },
    limit: {
      type: Number,
      default: 5000,
    },
    field: {
      type: Object,
    },
  },

  emits: ['change'],

  computed: {
    size() {
      return {
        sm: 300,
        md: 600,
        lg: 1000,
      }[this.field.size ?? 'md'];
    },
  },

  data() {
    return {
      initConfig: {
        selector: 'textarea#open-source-plugins',
        plugins:
          'paste importcss searchreplace autolink autosave save image link media template table nonbreaking advlist lists wordcount help charmap quickbars code imagetools',
        imagetools_cors_hosts: ['picsum.photos'],
        menubar: 'file edit view insert format tools table help',
        toolbar:
          'undo redo | formatselect | bold italic forecolor | link image | alignleft aligncenter alignright alignjustify | bullist numlist | removeformat code',
        toolbar_sticky: true,
        autosave_ask_before_unload: false,
        autosave_interval: '30s',
        autosave_prefix: '{path}{query}-{id}-',
        autosave_restore_when_empty: false,
        autosave_retention: '2m',
        image_advtab: true,
        importcss_append: true,
        height: 600,
        image_caption: true,
        quickbars_selection_toolbar: 'bold italic | quicklink h2 h3 blockquote quickimage quicktable',
        noneditable_noneditable_class: 'mceNonEditable',
        toolbar_mode: 'sliding',
        contextmenu: 'link image imagetools table',
        skin: 'oxide',
        content_css: 'default',
        content_style: 'body { font-family:Helvetica,Arial,sans-serif; font-size:14px }',
        paste_data_images: true,
        images_upload_handler(blobInfo, success, failure, progress) {
          const data = new FormData();
          const file = new File([blobInfo.blob()], blobInfo.filename());
          data.append('files[]', file);
          data.append('folder', window.location.pathname.split('/')[1]);

          axios.post(route('sidebar.media.store'), data).then((response) => {
            if (response.status === 200) {
              success(response.data.data[0].path);
            }
          });
        },
      },
    };
  },
};
</script>
