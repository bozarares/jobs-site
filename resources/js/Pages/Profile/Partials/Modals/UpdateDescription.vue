<script setup>
import { useForm, usePage } from '@inertiajs/vue3';
import { onMounted, ref } from 'vue';
import { Button } from '@/Components/UI';
import Input from '@/Components/UI/Input/Input.vue';
import { UserIcon } from '@heroicons/vue/24/outline';
import { QuillEditor } from '@vueup/vue-quill';
import '@vueup/vue-quill/dist/vue-quill.snow.css';

const props = defineProps({
    closeModal: { type: Function, default: () => {} },
});
const quillRef = ref(null);

const page = usePage();
const user = page.props.auth.user;

const form = useForm({
    description: user.description,
});

const submit = () => {
    const htmlContent = quillRef.value.getHTML();
    form.description = htmlContent;
    form.post(route('profile.update.description'), {
        onFinish: () => {
            props.closeModal();
        },
    });
};
onMounted(() => {
    quillRef.value.setHTML(user.description);
});
</script>

<template>
    <Teleport to="body">
        <div class="fixed inset-0 flex items-center justify-center z-50">
            <!-- Backdrop -->
            <div
                @click="closeModal()"
                class="absolute inset-0 bg-black opacity-50"
            ></div>
            <div
                class="relative container flex flex-col gap-8 bg-white p-8 rounded shadow max-w-lg mx-auto overflow-auto max-h-[35em]"
            >
                <h2 class="text-lg font-bold uppercase text-black/60">
                    Edit User
                </h2>

                <div class="overflow-auto flex flex-col">
                    <QuillEditor
                        name="Company description"
                        ref="quillRef"
                        toolbar="essential"
                        theme="snow"
                    />
                </div>
                <Button
                    @click="submit"
                    class=""
                    :options="{ color: 'green', shape: 'pill' }"
                    >Save</Button
                >
            </div>
        </div>
    </Teleport>
</template>
