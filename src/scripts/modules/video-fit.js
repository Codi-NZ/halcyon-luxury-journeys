import { elSetCSS } from '../utils/element-helpers'

/**
 * Video Fit
 * v1.0.0
 */
const videoFit = () => {
    const videoFitEls = document.querySelectorAll('[data-video-fit]')
    let videoFrameEl, w, h, ratio, fW, fH, fRatio

    const resizeVideos = () => {
        videoFitEls.forEach((el) => {
            videoFrameEl = el.querySelector('iframe')
            w = el.offsetWidth
            h = el.offsetHeight
            ratio = w / h
            fW = videoFrameEl.getAttribute('width')
            fH = videoFrameEl.getAttribute('height')
            fRatio = fW / fH

            if (w === 0) return

            if (fRatio > ratio) {
                elSetCSS(videoFrameEl, {
                    width: `${Math.ceil(h * fRatio) + 1}px`,
                    height: `${Math.ceil(h) + 1}px`,
                    transform: `translateX(${-Math.ceil((h * fRatio - w) / 2)}px)`,
                })
            } else {
                elSetCSS(videoFrameEl, {
                    width: `${Math.ceil(w) + 1}px`,
                    height: `${Math.ceil(w / fRatio) + 1}px`,
                    transform: `translateY(${-Math.ceil((w / fRatio - h) / 2)}px)`,
                })
            }
        })
    }

    window.addEventListener('resize', resizeVideos)
    resizeVideos()
}

document.addEventListener('DOMContentLoaded', videoFit)

export default videoFit
