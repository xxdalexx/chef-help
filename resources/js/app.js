require('./bootstrap');

var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
    return new bootstrap.Tooltip(tooltipTriggerEl)
})

Livewire.on('showToast', id => {
    new bootstrap.Toast(document.getElementById(id)).show()
})
