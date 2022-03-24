export default {
    waitFunc: function(time, value) {
        return setTimeout(() => {
            value
        }, time)
    }
}