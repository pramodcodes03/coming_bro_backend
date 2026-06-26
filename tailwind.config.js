/** @type {import('tailwindcss').Config} */
module.exports = {
    content: ["./resources/**/*.blade.php",
    "./resources/**/*.js",
    "./resources/**/*.vue"],
    darkMode: "class",
    theme: {
      container: {
        center: true,
      },
      extend: {
        colors: {
          primary: {
            DEFAULT: "#018DBD",
            light: "#e6f4f9",
            "dark-light": "rgba(1,141,189,.15)",
          },
          secondary: {
            DEFAULT: "#13C3C3",
            light: "#e6f9f9",
            "dark-light": "rgba(19,195,195,.15)",
          },
          success: {
            DEFAULT: "#00ab55",
            light: "#ddf5f0",
            "dark-light": "rgba(0,171,85,.15)",
          },
          danger: {
            DEFAULT: "#e7515a",
            light: "#fff5f5",
            "dark-light": "rgba(231,81,90,.15)",
          },
          warning: {
            DEFAULT: "#e2a03f",
            light: "#fff9ed",
            "dark-light": "rgba(226,160,63,.15)",
          },
          info: {
            DEFAULT: "#2196f3",
            light: "#e7f7ff",
            "dark-light": "rgba(33,150,243,.15)",
          },
          dark: {
            DEFAULT: "#3b3f5c",
            light: "#eaeaec",
            "dark-light": "rgba(59,63,92,.15)",
          },
          black: {
            DEFAULT: "#0e1726",
            light: "#e3e4eb",
            "dark-light": "rgba(14,23,38,.15)",
          },
          white: {
            DEFAULT: "#ffffff",
            light: "#e0e6ed",
            dark: "#888ea8",
          },
        },
        fontFamily: {
          nunito: ["Nunito", "sans-serif"],
          // Marketing/site fonts
          display: ["Sora", "sans-serif"],
          sans: ["Inter", "ui-sans-serif", "system-ui", "sans-serif"],
        },
        spacing: {
          4.5: "18px",
        },
        // Brand palette for the public marketing site
        backgroundImage: {
          "brand-grad": "linear-gradient(115deg, #2b6fff, #6d4bff)",
          "brand-grad-text": "linear-gradient(110deg, #2b6fff 0%, #6d4bff 55%, #00c2a8 110%)",
        },
        boxShadow: {
          "3xl":
            "0 2px 2px rgb(224 230 237 / 46%), 1px 6px 7px rgb(224 230 237 / 46%)",
          // Soft, layered "luxury" shadows for marketing UI
          soft: "0 2px 6px -1px rgba(13,28,58,.06), 0 1px 2px rgba(13,28,58,.04)",
          card: "0 10px 30px -12px rgba(20,40,90,.16), 0 2px 8px -4px rgba(20,40,90,.08)",
          lift: "0 30px 70px -30px rgba(24,46,96,.28), 0 8px 24px -12px rgba(24,46,96,.14)",
          glow: "0 14px 34px -12px rgba(43,111,255,.5)",
        },
        keyframes: {
          floaty: {
            "0%,100%": { transform: "translateY(0)" },
            "50%": { transform: "translateY(-14px)" },
          },
          devfloat: {
            "0%,100%": { transform: "translateY(0)" },
            "50%": { transform: "translateY(-12px)" },
          },
          carfloat: {
            "0%,100%": { transform: "translate(-50%,-50%)" },
            "50%": { transform: "translate(-46%,-58%)" },
          },
          marquee: {
            "0%": { transform: "translateX(0)" },
            "100%": { transform: "translateX(-50%)" },
          },
          spinslow: { to: { transform: "rotate(360deg)" } },
          gradshift: {
            "0%": { backgroundPosition: "0% 50%" },
            "100%": { backgroundPosition: "100% 50%" },
          },
        },
        animation: {
          floaty: "floaty 5s cubic-bezier(.22,1,.36,1) infinite",
          "floaty-rev": "floaty 6s cubic-bezier(.22,1,.36,1) infinite reverse",
          devfloat: "devfloat 6s cubic-bezier(.22,1,.36,1) infinite",
          carfloat: "carfloat 3.5s cubic-bezier(.22,1,.36,1) infinite",
          marquee: "marquee 28s linear infinite",
          spinslow: "spinslow 26s linear infinite",
          gradshift: "gradshift 9s ease infinite alternate",
        },
        typography: {
          DEFAULT: {
            css: {
              h1: { fontSize: "40px" },
              h2: { fontSize: "32px" },
              h3: { fontSize: "28px" },
              h4: { fontSize: "24px" },
              h5: { fontSize: "20px" },
              h6: { fontSize: "16px" },
            },
          },
        },
      },
    },
    plugins: [
      require("@tailwindcss/forms")({
        strategy: "base",
      }),
      require("@tailwindcss/typography"),
    ],
  };
