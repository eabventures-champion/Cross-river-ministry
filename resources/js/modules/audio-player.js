/** Custom-styled audio player wrapping a native <audio x-ref="audio"> element. */
export default () => ({
    playing: false,
    current: 0,
    duration: 0,
    init() {
        const a = this.$refs.audio;
        if (!a) return;
        a.addEventListener('loadedmetadata', () => (this.duration = a.duration || 0));
        a.addEventListener('timeupdate', () => (this.current = a.currentTime));
        a.addEventListener('ended', () => (this.playing = false));
        a.addEventListener('pause', () => (this.playing = false));
        a.addEventListener('play', () => {
            // Pause any other player on the page.
            document.querySelectorAll('audio').forEach((el) => { if (el !== a) el.pause(); });
            this.playing = true;
        });
    },
    toggle() {
        const a = this.$refs.audio;
        a.paused ? a.play() : a.pause();
    },
    seek(e) {
        const rect = e.currentTarget.getBoundingClientRect();
        const ratio = Math.min(Math.max((e.clientX - rect.left) / rect.width, 0), 1);
        if (this.duration) this.$refs.audio.currentTime = ratio * this.duration;
    },
    skip(seconds) {
        this.$refs.audio.currentTime = Math.max(0, this.$refs.audio.currentTime + seconds);
    },
    get progress() {
        return this.duration ? (this.current / this.duration) * 100 : 0;
    },
    format(t) {
        if (!t || !isFinite(t)) return '0:00';
        const m = Math.floor(t / 60);
        const s = Math.floor(t % 60).toString().padStart(2, '0');
        return `${m}:${s}`;
    },
});
