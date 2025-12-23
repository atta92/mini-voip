<script setup>
import { ref, onMounted } from 'vue'
import { usePage } from '@inertiajs/vue3'
import axios from 'axios'

const page = usePage()
const user = ref({
    ...page.props.user,
    balance: Number(page.props.user.balance ?? 0)
})


const calls = ref(page.props.calls || []);

const currentCall = ref(null)
const duration = ref(0)
let timer = null

const fetchCalls = () => {
    axios.get('/calls').then(res => {
        calls.value = res.data
    })
}

const startCall = () => {
    axios.post('/call/start').then(res => {
        currentCall.value = res.data
        duration.value = 0

        timer = setInterval(() => {
            duration.value++
        }, 1000)
    })
}
      
const endCall = () => {
    axios.post(`/call/end/${currentCall.value.id}`).then(res => {
        clearInterval(timer)
        currentCall.value = null
        user.value.balance -= res.data.cost
        fetchCalls()
    })
}

onMounted(fetchCalls)
</script>


<template>
  <div class="p-6 max-w-2xl mx-auto">
    <h1 class="text-2xl font-bold mb-4">VoIP Dashboard</h1>

    <p class="mb-2 font-semibold">
        Balance: ${{ user.balance.toFixed(2) }}
    </p>

    <p v-if="currentCall" class="mb-3">
        Call Duration: {{ duration }} sec
    </p>

    <button
      v-if="!currentCall"
      @click="startCall"
      class="bg-green-600 text-white px-4 py-2 rounded"
    >
      Start Call
    </button>

    <button
      v-else
      @click="endCall"
      class="bg-red-600 text-white px-4 py-2 rounded"
    >
      End Call
    </button>

    <h2 class="text-xl mt-6 mb-2 font-semibold">Call History</h2>

    <ul class="space-y-1">

      <li v-for="call in calls" :key="call.id" class="text-sm">
        Call #{{ call.id }} —
        {{ call.duration_seconds ?? 0 }}s —
        ${{ call.cost ?? 0 }}
      </li>

    </ul>
    
  </div>
</template>