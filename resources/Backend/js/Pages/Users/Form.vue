<template>
  <Form :form="form" :rules="rules" :hideAction="true">
    <div class="space-y-6 card">
      <Fieldset
        v-model="form.name"
        :field="{
          label: 'Name',
          readonly: true,
        }"
      />
      <Fieldset
        v-model="form.email"
        :field="{
          label: 'Email',
          readonly: true,
        }"
      />
      <Fieldset
        v-model="form.phone"
        :field="{
          label: 'Số điện thoại',
          readonly: true,
        }"
      />
      <Fieldset
        v-model="form.formatted_created_at"
        :field="{
          label: 'Ngày tạo',
          readonly: true,
        }"
      />
    </div>
    <!-- <div class="mt-10 space-y-6 bg-white rounded">
            <table class="table">
                <tr>
                    <th>Mã hồ sơ</th>
                    <th>Trạng thái</th>
                    <th>Thời gian công chứng</th>
                    <th>Ngày tạo</th>
                    <th></th>
                </tr>
                <tr v-for="(document, index) in item.documents">
                    <td>
                        <Link
                            class="flex items-center break-words whitespace-pre-wrap hover:text-primary focus:text-primary-dark"
                            :href="
                                route(`documents.form`, {
                                    id: document.id,
                                })
                            "
                        >
                            {{ document.code }}
                        </Link>
                    </td>
                    <td>
                        <Link
                            class="flex items-center break-words whitespace-pre-wrap hover:text-primary focus:text-primary-dark"
                            :href="
                                route(`documents.form`, {
                                    id: document.id,
                                })
                            "
                        >
                            {{ document.formatted_status }}
                        </Link>
                    </td>
                    <td>
                        <Link
                            class="flex items-center break-words whitespace-pre-wrap hover:text-primary focus:text-primary-dark"
                            :href="
                                route(`documents.form`, {
                                    id: document.id,
                                })
                            "
                        >
                            {{ document.expected_datetime }}
                        </Link>
                    </td>
                    <td>
                        <Link
                            class="flex items-center break-words whitespace-pre-wrap hover:text-primary focus:text-primary-dark"
                            :href="
                                route(`documents.form`, {
                                    id: document.id,
                                })
                            "
                        >
                            {{ document.formatted_created_at }}
                        </Link>
                    </td>
                    <td class="border-t">
                        <Link
                            class="flex items-center px-4"
                            :href="
                                route(`documents.form`, {
                                    id: document.id,
                                })
                            "
                            tabindex="-1"
                        >
                            <IconCheveronRight
                                class="block w-6 h-6 text-gray-400"
                            />
                        </Link>
                    </td>
                </tr>
            </table>
        </div> -->
  </Form>
</template>

<script>
import Authenticated from '@/Layouts/Authenticated';
export default {
  layout: Authenticated,
  props: ['item', 'rules'],
  data() {
    return {
      form: this.$inertia.form({ ...this.item }),
    };
  },
};
</script>
