<script setup>
import {computed,ref,onMounted,watch} from 'vue';
import { router,Link } from '@inertiajs/vue3';
const props = defineProps({USERS:Object,AUTH:Object,CONVERSATION:Object,NEWMSG:Object,LASTMSG:Object})
const emit = defineEmits(['update-conversation']);

// search users in modal
const searchText = ref(null);
const filteredUsers = computed(()=>{
          if( searchText.value ) {
                return props.USERS.filter( item => Object.entries(item)
                                                            .reduce( (result, [, value]) => !(value instanceof Object) ? result += ` ${value}` : result, '')
                                                            .toLowerCase()
                                                            .includes( searchText.value.toLowerCase() ));
            }
        return props.USERS
    })

// conversation

// access modal
const my_modal_1 = ref(null);


const CreateConversation = (user) => {
    const conversation = {
        user_id: props.AUTH.user.id,
        participantOne: user.id,
        participantTwo: props.AUTH.user.id,
        last_message: '..'
    }
    // close modal
    my_modal_1.value.close();
    router.post("/conversation",conversation);
}


// update conversation
const updateConversation = (conversation)=>{
    emit('update-conversation',conversation);
}

//detect active user
const all_convs = ref([]);

onMounted(()=>{
    all_convs.value = props.CONVERSATION;
    setInterval(detectActiveUser,2000);
});
// on incoming msg, all_convs is updated
watch(()=>props.NEWMSG,(newVal)=>{


    all_convs.value = all_convs.value?.map(con => {
                if(con.participantOneId == newVal.sender){
                    return {
                        ...con,
                        participantOneLastActivity: "date "+new Date().toLocaleTimeString("en-US",{hour12:false}),
                        last_message: newVal.message
                    }
                }else if(con.participantTwoId == newVal.sender){
                    return {
                        ...con,
                        participantTwoLastActivity: "date "+new Date().toLocaleTimeString("en-US",{hour12:false}),
                        last_message: newVal.message
                    }
                }else{
                    return {
                        ...con
                    }
                }
            })
},{immediate:true});


const detectActiveUser = () => {
    all_convs.value = all_convs.value?.map(item => {
        return {
            ...item,
            active: activeUser(item)
        }
    })
}
const activeUser = (con) => {
    const currentDateTime = new Date();
    const targetDateTime = new Date();
    const userTime = props.AUTH.user.id == con.participantOneId?con.participantTwoLastActivity:con.participantOneLastActivity;
    let time = userTime?.split(' ')[1];
    const targetTimeParts = time?.split(':');
    if(targetTimeParts && targetTimeParts.length){
        targetDateTime.setHours(targetTimeParts[0]);
        targetDateTime.setMinutes(parseInt(targetTimeParts[1])+2);
        targetDateTime.setSeconds(targetTimeParts[2]);
    }
    if(targetDateTime>=currentDateTime){

        return true;
    }
    return false;
}

// update last msg on user side
watch(()=>props.LASTMSG,(newVal)=>{
    all_convs.value = all_convs.value?.map(con => {
                if(con.participantOneId == newVal.sender){
                    return {
                        ...con,
                        last_message: newVal.message
                    }
                }else if(con.participantTwoId == newVal.sender){
                    return {
                        ...con,
                        last_message: newVal.message
                    }
                }else{
                    return {
                        ...con
                    }
                }
            })
})
</script>


<template>
<div
        class="w-[100px] border-r border-t-0 border-gray-300 lg:col-span-1 md:w-full"
    >
        <div
            class="h-[65px] text-center text-grey-500 p-4 border-b border-gray-300 flex md:justify-end justify-center"
        >
            <svg
                viewBox="0 0 194.436 194.436"
                class="w-5 h-5 text-grey-500"
                onclick="my_modal_1.showModal()"
            >
                <path
                    d="M192.238,34.545L159.894,2.197C158.487,0.79,156.579,0,154.59,0c-1.989,0-3.897,0.79-5.303,2.196l-32.35,32.35
        c-0.004,0.004-0.008,0.01-0.013,0.014L54.876,96.608c-1.351,1.352-2.135,3.166-2.193,5.076l-1.015,33.361
        c-0.063,2.067,0.731,4.068,2.193,5.531c1.409,1.408,3.317,2.196,5.303,2.196c0.076,0,0.153-0.001,0.229-0.004l33.36-1.018
        c1.909-0.058,3.724-0.842,5.075-2.192l94.41-94.408C195.167,42.223,195.167,37.474,192.238,34.545z M154.587,61.587L132.847,39.85
        l21.743-21.743l21.738,21.741L154.587,61.587z M89.324,126.85l-22.421,0.685l0.682-22.422l54.655-54.656l21.741,21.738
        L89.324,126.85z"
                />
                <path
                    d="M132.189,117.092c-4.142,0-7.5,3.357-7.5,7.5v54.844H15.001V69.748h54.844c4.142,0,7.5-3.357,7.5-7.5s-3.358-7.5-7.5-7.5
        H7.501c-4.142,0-7.5,3.357-7.5,7.5v124.687c0,4.143,3.358,7.5,7.5,7.5h124.687c4.142,0,7.5-3.357,7.5-7.5v-62.344
        C139.689,120.449,136.331,117.092,132.189,117.092z"
                />
            </svg>
        </div>


        <dialog id="my_modal_1" class="modal" ref="my_modal_1" >
                <div class="modal-box">
                  <div class="body p-2 h-80 ">
                    <input type="text" v-model="searchText" placeholder="Search a user..." class="input input-bordered w-full rounded mb-2" />
                    <ul class="h-64 overflow-scroll overflow-x-hidden scroll-width">
                        <li class="p-4 my-1 hover:bg-slate-100 cursor-pointer"
                        v-for="(user,index) in filteredUsers" :key="index"
                        @click="CreateConversation(user)"
                        >
                            {{ user?.name }}
                        </li>
                    </ul>
                  </div>
                  <div class="modal-action">
                    <form method="dialog" >
                      <!-- if there is a button in form, it will close the modal -->
                      <button class="btn w-[150px]" ref="my_modal_1_close_btn">Close</button>
                    </form>
                  </div>
                </div>
            </dialog>



        <ul class="overflow-auto">
            <li>
                <Link :href="route('get.messages',con.id)"
                    class="flex items-center px-3 py-2 text-sm transition duration-150 ease-in-out border-b border-gray-300 cursor-pointer hover:bg-gray-100 focus:outline-none"
                    v-for="(con,index) in all_convs" :key="index"
                    @click="updateConversation(con)"
                    >
                    <img
                        class="object-cover w-10 h-10 rounded-full"
                        src="https://cdn.pixabay.com/photo/2018/09/12/12/14/man-3672010__340.jpg"
                        alt="username"
                    />
                    <span v-if="con.active"
                        class="relative w-3 h-3 bg-green-600 rounded-full left-[-5px] top-2"
                    >
                    </span>
                    <span v-else
                        class="relative w-3 h-3 bg-gray-600 rounded-full left-[-5px] top-2"
                    >
                    </span>
                    <div class="w-full pb-2 hidden md:block" v-if="con.participantOneId != AUTH.user.id">
                        <div class="flex justify-between">
                            <span
                                class="block ml-2 font-semibold text-gray-600"
                                >{{ con.participantOne }}</span
                            >
                            <span
                                class="block ml-2 text-sm text-gray-600"
                                >25 minutes</span
                            >
                        </div>
                        <span
                            class="block ml-2 text-sm text-gray-600"
                            >{{ con.last_message }}</span
                        >
                    </div>
                    <div class="w-full pb-2 hidden md:block" v-else>
                        <div class="flex justify-between">
                            <span
                                class="block ml-2 font-semibold text-gray-600"
                                >{{ con.participantTwo }}</span
                            >
                            <span
                                class="block ml-2 text-sm text-gray-600"
                                >25 minutes</span
                            >
                        </div>
                        <span
                            class="block ml-2 text-sm text-gray-600"
                            >{{ con.last_message }}</span
                        >
                    </div>
                </Link>
            </li>
        </ul>
    </div>

</template>

<style src="./../../Pages/Auth/login.css" scoped>

</style>
