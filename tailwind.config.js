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
        cream: {
          50: '#fdfcfb',
          100: '#faf8f6',
          200: '#f5f2ee',
          300: '#ede7e0',
          400: '#e1d6cb',
          500: '#d2c4b2',
          600: '#c0b098',
          700: '#a89680',
          800: '#8a7b68',
          900: '#6f6454',
        },
        neutral: {
          50: '#ffffff',
          100: '#f9fafb',
          200: '#f3f4f6',
          300: '#e5e7eb',
          400: '#9ca3af',
          500: '#6b7280',
          600: '#4b5563',
          700: '#374151',
          800: '#1f2937',
          900: '#111827',
        },
      },
      fontFamily: {
        'sans': ['Inter', 'ui-sans-serif', 'system-ui'],
        'serif': ['Playfair Display', 'Merriweather', 'ui-serif', 'Georgia'],
      }
    },
  },
  plugins: [
    require('@tailwindcss/forms'),
  ],
}