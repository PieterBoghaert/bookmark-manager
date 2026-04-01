import "./bootstrap";
import Alpine from "alpinejs";

window.Alpine = Alpine;
Alpine.start();

// View Transition API support
document.addEventListener("livewire:navigating", () => {
    if (document.startViewTransition) {
        document.startViewTransition(() => {
            // Livewire will handle the DOM updates
        });
    }
});
