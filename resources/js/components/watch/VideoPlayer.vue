<script setup lang="ts">
import { ref, onMounted, watch } from 'vue'
import { Play, Pause, Volume2, VolumeX, Maximize, SkipBack, SkipForward, Settings } from 'lucide-vue-next'
import { Button } from '@/components/ui/button'
import { Progress } from '@/components/ui/progress'

interface Props {
    src?: string
    autoplay?: boolean
}

const props = withDefaults(defineProps<Props>(), {
    autoplay: false
})

const emit = defineEmits<{
    timeUpdate: [currentTime: number, duration: number]
    ended: []
    play: []
    pause: []
}>()

const videoRef = ref<HTMLVideoElement>()
const isPlaying = ref(false)
const currentTime = ref(0)
const duration = ref(0)
const volume = ref(1)
const isMuted = ref(false)
const isFullscreen = ref(false)
const showControls = ref(true)
const playbackRate = ref(1)
const showSettings = ref(false)

let controlsTimeout: NodeJS.Timeout

const formatTime = (time: number): string => {
    const hours = Math.floor(time / 3600)
    const minutes = Math.floor((time % 3600) / 60)
    const seconds = Math.floor(time % 60)

    if (hours > 0) {
        return `${hours}:${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`
    }
    return `${minutes}:${seconds.toString().padStart(2, '0')}`
}

const togglePlay = () => {
    if (!videoRef.value) return

    if (isPlaying.value) {
        videoRef.value.pause()
    } else {
        videoRef.value.play()
    }
}

const toggleMute = () => {
    if (!videoRef.value) return

    isMuted.value = !isMuted.value
    videoRef.value.muted = isMuted.value
}

const setVolume = (newVolume: number) => {
    if (!videoRef.value) return

    volume.value = newVolume
    videoRef.value.volume = newVolume
    isMuted.value = newVolume === 0
}

const seek = (time: number) => {
    if (!videoRef.value) return

    videoRef.value.currentTime = time
}

const skipForward = () => {
    if (!videoRef.value) return

    videoRef.value.currentTime = Math.min(videoRef.value.currentTime + 10, duration.value)
}

const skipBackward = () => {
    if (!videoRef.value) return

    videoRef.value.currentTime = Math.max(videoRef.value.currentTime - 10, 0)
}

const toggleFullscreen = () => {
    if (!document.fullscreenElement) {
        videoRef.value?.requestFullscreen()
        isFullscreen.value = true
    } else {
        document.exitFullscreen()
        isFullscreen.value = false
    }
}

const setPlaybackRate = (rate: number) => {
    if (!videoRef.value) return

    playbackRate.value = rate
    videoRef.value.playbackRate = rate
    showSettings.value = false
}

const showControlsTemporarily = () => {
    showControls.value = true
    clearTimeout(controlsTimeout)
    controlsTimeout = setTimeout(() => {
        if (isPlaying.value) {
            showControls.value = false
        }
    }, 3000)
}

const onTimeUpdate = () => {
    if (!videoRef.value) return

    currentTime.value = videoRef.value.currentTime
    duration.value = videoRef.value.duration || 0
    emit('timeUpdate', currentTime.value, duration.value)
}

const onPlay = () => {
    isPlaying.value = true
    emit('play')
    showControlsTemporarily()
}

const onPause = () => {
    isPlaying.value = false
    emit('pause')
    showControls.value = true
}

const onEnded = () => {
    isPlaying.value = false
    emit('ended')
}

const onLoadedMetadata = () => {
    if (!videoRef.value) return
    duration.value = videoRef.value.duration
}

onMounted(() => {
    if (videoRef.value) {
        videoRef.value.volume = volume.value

        if (props.autoplay) {
            videoRef.value.play()
        }
    }
})

watch(() => props.src, (newSrc) => {
    if (videoRef.value && newSrc) {
        videoRef.value.load()
    }
})
</script>

<template>
    <div
        class="relative bg-black rounded-lg overflow-hidden group"
        @mousemove="showControlsTemporarily"
        @mouseleave="isPlaying && (showControls = false)"
    >
        <video
            ref="videoRef"
            :src="src"
            class="w-full h-full object-contain"
            @timeupdate="onTimeUpdate"
            @play="onPlay"
            @pause="onPause"
            @ended="onEnded"
            @loadedmetadata="onLoadedMetadata"
            @click="togglePlay"
        />

        <!-- Loading overlay -->
        <div
            v-if="!duration"
            class="absolute inset-0 flex items-center justify-center bg-black/50"
        >
            <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-white"></div>
        </div>

        <!-- Controls overlay -->
        <div
            v-show="showControls || !isPlaying"
            class="absolute inset-0 bg-gradient-to-t from-black/80 via-transparent to-black/20 transition-opacity duration-300"
        >
            <!-- Center play button -->
            <div class="absolute inset-0 flex items-center justify-center">
                <Button
                    v-if="!isPlaying"
                    @click="togglePlay"
                    size="lg"
                    class="bg-white/20 hover:bg-white/30 text-white border-0 backdrop-blur-sm"
                >
                    <Play class="w-8 h-8" />
                </Button>
            </div>

            <!-- Bottom controls -->
            <div class="absolute bottom-0 left-0 right-0 p-4 space-y-2">
                <!-- Progress bar -->
                <div class="flex items-center space-x-2">
                    <span class="text-white text-sm font-mono">{{ formatTime(currentTime) }}</span>
                    <div class="flex-1 cursor-pointer" @click="seek(($event.offsetX / $event.currentTarget.offsetWidth) * duration)">
                        <Progress
                            :model-value="duration ? (currentTime / duration) * 100 : 0"
                            class="h-1 bg-white/20"
                        />
                    </div>
                    <span class="text-white text-sm font-mono">{{ formatTime(duration) }}</span>
                </div>

                <!-- Control buttons -->
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-2">
                        <Button
                            @click="togglePlay"
                            size="sm"
                            variant="ghost"
                            class="text-white hover:bg-white/20"
                        >
                            <Play v-if="!isPlaying" class="w-4 h-4" />
                            <Pause v-else class="w-4 h-4" />
                        </Button>

                        <Button
                            @click="skipBackward"
                            size="sm"
                            variant="ghost"
                            class="text-white hover:bg-white/20"
                        >
                            <SkipBack class="w-4 h-4" />
                        </Button>

                        <Button
                            @click="skipForward"
                            size="sm"
                            variant="ghost"
                            class="text-white hover:bg-white/20"
                        >
                            <SkipForward class="w-4 h-4" />
                        </Button>

                        <div class="flex items-center space-x-2">
                            <Button
                                @click="toggleMute"
                                size="sm"
                                variant="ghost"
                                class="text-white hover:bg-white/20"
                            >
                                <Volume2 v-if="!isMuted && volume > 0" class="w-4 h-4" />
                                <VolumeX v-else class="w-4 h-4" />
                            </Button>

                            <div class="w-20 cursor-pointer" @click="setVolume($event.offsetX / $event.currentTarget.offsetWidth)">
                                <Progress
                                    :model-value="isMuted ? 0 : volume * 100"
                                    class="h-1 bg-white/20"
                                />
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center space-x-2">
                        <div class="relative">
                            <Button
                                @click="showSettings = !showSettings"
                                size="sm"
                                variant="ghost"
                                class="text-white hover:bg-white/20"
                            >
                                <Settings class="w-4 h-4" />
                            </Button>

                            <!-- Settings dropdown -->
                            <div
                                v-if="showSettings"
                                class="absolute bottom-full right-0 mb-2 bg-black/90 backdrop-blur-sm rounded-lg p-2 min-w-32"
                            >
                                <div class="text-white text-sm font-medium mb-2">Playback Speed</div>
                                <div class="space-y-1">
                                    <button
                                        v-for="rate in [0.5, 0.75, 1, 1.25, 1.5, 2]"
                                        :key="rate"
                                        @click="setPlaybackRate(rate)"
                                        class="block w-full text-left px-2 py-1 text-sm text-white hover:bg-white/20 rounded"
                                        :class="{ 'bg-white/20': playbackRate === rate }"
                                    >
                                        {{ rate }}x
                                    </button>
                                </div>
                            </div>
                        </div>

                        <Button
                            @click="toggleFullscreen"
                            size="sm"
                            variant="ghost"
                            class="text-white hover:bg-white/20"
                        >
                            <Maximize class="w-4 h-4" />
                        </Button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
