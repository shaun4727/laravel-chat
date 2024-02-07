<script setup>
import {computed,ref,watch,onMounted} from 'vue';
import { router } from '@inertiajs/vue3';


const props = defineProps({CONVERSATION:Object,MESSAGES:Object,AUTH:Object,NEWMSG:Object});
const emit = defineEmits(['update-sidebar-msg']);


// load messages
const all_msgs = ref([]);
const typingUser = ref(null);
const clearInt = ref(null);

onMounted(()=>{
    all_msgs.value= props.MESSAGES;

    // listen to typing event
    window.Echo.private(`chats.${props.AUTH.user.id}`).listenForWhisper('typing',(e)=>{
            if(clearInt.value){
                clearInterval(clearInt.value);
            }
            typingUser.value = e.name+" typing...";
            clearInt.value = setInterval(()=>{
                typingUser.value = null;
            },3000);
        })



})

watch(()=>props.NEWMSG,(newVal)=>{
    if(newVal){
        all_msgs.value.push(newVal);

    }
},{immediate:true})

// send message
const message = ref(null);
const sendMessage = (con) => {
    const msg = {
        conversation_id : con.id,
        sender: props.AUTH.user.id,
        receiver: props.AUTH.user.id === con.participantOneId?con.participantTwoId:con.participantOneId,
        message: message.value
    }
    emit('update-sidebar-msg',msg);
    all_msgs.value.push(msg);
    message.value = '';
    router.post("/message",msg);
}

// listen for typing

const getTypingEvent = (con) => {
    // window.Echo.private(`chats`).whisper('typing',{
    window.Echo.private(`chats.${props.AUTH.user.id === con.participantOneId?con.participantTwoId:con.participantOneId}`).whisper("typing",{
        name: props.AUTH.user.name
    });
    // clearInterval(clearInt.value);

}




</script>

<template>
<div class="w-full lg:col-span-2 lg:block" >
    <div class="w-full grid conversation-row-grid" v-if="CONVERSATION?.id">
        <div
            class="relative flex items-center p-3 border-b border-gray-300"
        >
        <img
            class="object-cover w-10 h-10 rounded-full"
            src="https://cdn.pixabay.com/photo/2018/09/12/12/14/man-3672010__340.jpg"
            alt="username"
        />
            <span class="block ml-2 font-bold text-gray-600"
                >{{ AUTH.user.id == CONVERSATION.participantOneId?CONVERSATION?.participantTwo:CONVERSATION?.participantOne }}</span
            >
        </div>
        <div class=" relative w-full p-6 overflow-y-auto" v-chat-scroll>
            <ul class="space-y-2" id="scroll-area">
                <template v-for="(msg,index) in all_msgs" :key="index" >
                    <li class="flex justify-start" v-if="AUTH.user.id != msg.sender">
                        <div
                            class="relative max-w-xl px-4 py-2 text-gray-700 rounded shadow"
                        >
                            <span class="block">{{ msg.message }}</span>
                        </div>
                    </li>
                    <li class="flex justify-end" v-else>
                        <div
                            class="relative max-w-xl px-4 py-2 text-gray-700 bg-gray-100 rounded shadow"
                        >
                            <span class="block">{{ msg.message }}</span>
                        </div>
                    </li>
                </template>

            </ul>
            <p class="typing">{{ typingUser }}</p>
        </div>

        <div
            class="flex items-center justify-between w-full p-3 border-t border-gray-300"
        >
            <button>
                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    class="w-6 h-6 text-gray-500"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
                    />
                </svg>
            </button>

            <input
                type="text"
                placeholder="Message"
                class="block w-full py-2 pl-4 mx-3 bg-gray-100 focus:ring focus:ring-violet-500 rounded-full outline-none focus:text-gray-700"
                name="message"
                v-model="message"
                @keyup.enter="sendMessage(CONVERSATION)"
                @keydown = "getTypingEvent(CONVERSATION)"
                required
            />
            <button type="submit" @click="sendMessage(CONVERSATION)">
                <svg
                    class="w-5 h-5 text-gray-500 origin-center transform rotate-90"
                    xmlns="http://www.w3.org/2000/svg"
                    viewBox="0 0 20 20"
                    fill="currentColor"
                >
                    <path
                        d="M10.894 2.553a1 1 0 00-1.788 0l-7 14a1 1 0 001.169 1.409l5-1.429A1 1 0 009 15.571V11a1 1 0 112 0v4.571a1 1 0 00.725.962l5 1.428a1 1 0 001.17-1.408l-7-14z"
                    />
                </svg>
            </button>
        </div>
    </div>
    <div class="w-full grid conversation-row-grid" v-else>
        <div style="display: flex; justify-content: center; height: 90vh; align-items: center;">
            <i class="material-icons" style="font-size:150px; opacity: .4;">message</i>
        </div>
    </div>
</div>

</template>

<style src="./../../Pages/Auth/login.css" scoped>

</style>
