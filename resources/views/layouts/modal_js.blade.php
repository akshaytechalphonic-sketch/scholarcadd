 {{-- <script>
     function openNewModal(modalId) {
         const modal = document.getElementById(modalId);
         if (modal) {
             modal.classList.remove("hidden");
             modal.querySelector('.new-modal-content').classList.remove("opacity-0", "translate-y-20");
             modal.querySelector('.new-modal-content').classList.add("opacity-100", "translate-y-0");
         }
         modal.addEventListener("click", (event) => {
             if (!modal.querySelector('.new-modal-content').contains(event.target)) {
                 closeNewModal(modalId);
             }
         });
     }

     function closeNewModal(modalId) {
         const modal = document.getElementById(modalId);
         if (modal) {
             modal.querySelector('.new-modal-content').classList.remove("opacity-100", "translate-y-0");
             modal.querySelector('.new-modal-content').classList.add("opacity-0", "translate-y-20");
             setTimeout(() => {
                 modal.classList.add("hidden");
             }, 300);
         }
     }
 </script> --}}

 <script>
     function openNewModal(modalId) {
         const modal = document.getElementById(modalId);
         if (modal) {
             modal.classList.remove("hidden");
             const content = modal.querySelector('.new-modal-content');
             content.classList.remove("opacity-100", "translate-y-0");
             content.classList.add("opacity-0", "translate-y-20");
             requestAnimationFrame(() => {
                 content.classList.remove("opacity-0", "translate-y-20");
                 content.classList.add("opacity-100", "translate-y-0");
             });
             modal.addEventListener("click", (event) => {
                 if (!content.contains(event.target)) {
                     closeNewModal(modalId);
                 }
             });
         }
     }

     function closeNewModal(modalId) {
         const modal = document.getElementById(modalId);
         if (modal) {
             const content = modal.querySelector('.new-modal-content');
             content.classList.remove("opacity-100", "translate-y-0");
             content.classList.add("opacity-0", "translate-y-20");

             setTimeout(() => {
                 modal.classList.add("hidden");
             }, 300);
         }
     }
 </script>
