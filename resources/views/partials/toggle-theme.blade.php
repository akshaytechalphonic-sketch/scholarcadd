<button id="themeToggle" class="p-2 bg-gray-200 rounded-full">
    <span id="themeIcon" class="material-icons">light_mode</span>
  </button>
  <script>
    const themeToggle = document.getElementById('themeToggle');
    const html = document.documentElement;
  
    themeToggle.addEventListener('click', () => {
      html.classList.toggle('dark');
      localStorage.theme = html.classList.contains('dark') ? 'dark' : 'light';
      document.getElementById('themeIcon').textContent = html.classList.contains('dark') ? 'dark_mode' : 'light_mode';
    });
  
    if (localStorage.theme === 'dark' || (!localStorage.theme && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
      html.classList.add('dark');
      document.getElementById('themeIcon').textContent = 'dark_mode';
    }
  </script>
  