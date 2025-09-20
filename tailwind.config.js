/** @type {import('tailwindcss').Config} */
export default {
  content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
    "./resources/**/*.vue",
  ],
  theme: {
    extend: {
      colors: {
        primary: {
          50: '#f0f4f8',
          100: '#d9e2ec',
          200: '#bcccdc',
          300: '#9fb3c8',
          400: '#829ab1',
          500: '#405c6c', // Custom blue 1
          600: '#364f5c',
          700: '#2d404c',
          800: '#24323c',
          900: '#1a252c',
        },
        secondary: {
          50: '#f1f5fb',
          100: '#d9e7f5',
          200: '#b8d2ec',
          300: '#8bb9e0',
          400: '#6ba0d3',
          500: '#4d72b2', // Custom blue 2
          600: '#3f5e94',
          700: '#344d78',
          800: '#2a3d5c',
          900: '#1f2d40',
        },
        accent: {
          50: '#f8fafc',
          100: '#f1f5f9',
          200: '#e2e8f0',
          300: '#cbd5e1',
          400: '#94a3b8',
          500: '#64748b',
          600: '#475569',
          700: '#334155',
          800: '#1e293b',
          900: '#0f172a',
        }
      },
      fontFamily: {
        'sans': ['Inter', 'ui-sans-serif', 'system-ui'],
      }
    },
  },
  plugins: [
    require('@tailwindcss/forms'),
  ],
}