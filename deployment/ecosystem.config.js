module.exports = {
  apps: [
    {
      name: 'scholaracad',
      script: 'npm',
      args: 'start',
      cwd: '/var/www/scholaracad.com/ScholarAcadNextjs',
      interpreter: '/usr/bin/node',
      autorestart: true,
      watch: false,
      merge_logs: true,
      out_file: '/root/.pm2/logs/scholaracad-out.log',
      error_file: '/root/.pm2/logs/scholaracad-error.log',
    }
  ]
};
