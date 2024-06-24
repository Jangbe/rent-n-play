<script setup>
import { onMounted, defineEmits, ref } from 'vue';

const emit = defineEmits(['update:modelValue']);
const props = defineProps({
    modelValue: { type: String, required: true },
    length: { type: Number, default: 6 }
})

const result = ref([]);
for (let i = 0; i < props.length; i++) result.value.push('');
onMounted(() => {
    const inputs = document.querySelectorAll(".otp-field > input");

    window.addEventListener("load", () => inputs[0].focus());
    if(props.modelValue != '') inputs.forEach((node, i) => node.value = props.modelValue[i])

    inputs[0].addEventListener("paste", function (event) {
        event.preventDefault();

        const pastedValue = (event.clipboardData || window.clipboardData).getData(
            "text"
        );
        const otpLength = inputs.length;

        for (let i = 0; i < otpLength; i++) {
            if (i < pastedValue.length) {
                inputs[i].value = pastedValue[i];
                inputs[i].removeAttribute("disabled");
                inputs[i].focus;
            } else {
                inputs[i].value = ""; // Clear any remaining inputs
                inputs[i].focus;
            }
            result.value[i] = pastedValue[i];
        }
        emit('update:modelValue', result.value.join(''));
    });

    inputs.forEach((input, index1) => {
        input.addEventListener("keyup", (e) => {
            const currentInput = input;
            const nextInput = input.nextElementSibling;
            const prevInput = input.previousElementSibling;

            if (currentInput.value.length > 1) {
                currentInput.value = "";
                result.value[index1] = '';
                return;
            }

            if (
                nextInput &&
                nextInput.hasAttribute("disabled") &&
                currentInput.value !== ""
            ) {
                nextInput.removeAttribute("disabled");
                nextInput.focus();
            }

            result.value[index1] = currentInput.value;
            if (e.key === "Backspace") {
                inputs.forEach((input, index2) => {
                    if (index1 <= index2 && prevInput) {
                        input.setAttribute("disabled", true);
                        input.value = "";
                        prevInput.focus();
                    }
                });
                result.value[index1] = '';
            }

            if (result.value.filter(r => r != '').length == props.length)
                emit('update:modelValue', result.value.join(''))
            else
                emit('update:modelValue', '');
        });
    });
})
</script>

<template>
    <div class="form-group mb-3">
        <div class="otp-field mb-4">
            <input type="number" v-for="i in props.length" :disabled="i > 1" />
        </div>
    </div>
</template>

<style scoped>
.otp-field {
    flex-direction: row;
    column-gap: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.otp-field input {
    height: 45px;
    width: 42px;
    border-radius: 6px;
    outline: none;
    font-size: 1.125rem;
    text-align: center;
    border: 1px solid #ddd;
    -moz-appearance: textfield;
}

.otp-field input:focus {
    box-shadow: 0 1px 0 rgba(0, 0, 0, 0.1);
}

.otp-field input::-webkit-inner-spin-button,
.otp-field input::-webkit-outer-spin-button {
    display: none;
    -webkit-appearance: none;
}
</style>