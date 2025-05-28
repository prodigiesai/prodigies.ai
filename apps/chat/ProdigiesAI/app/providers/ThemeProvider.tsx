import React, { createContext, useContext, ReactNode, useState, useEffect } from 'react';
import { getTheme, setTheme as persistTheme } from '@/services/Locales'; // Adjust the import path as necessary

interface ThemeContextType {
    theme: 'light' | 'dark';
    toggleTheme: () => Promise<void>; // toggleTheme now is an async function
  }
  
  const ThemeContext = createContext<ThemeContextType>({
    theme: 'light', // This default value is less relevant because we set the state in useEffect
    toggleTheme: async () => {}, // Placeholder function
  });
  
  interface ThemeProviderProps {
    children: ReactNode;
}
  export const ThemeProvider: React.FC<ThemeProviderProps> = ({ children }) => {
        const [theme, setThemeTmp] = useState<'light' | 'dark'>('light');

        useEffect(() => {
            const loadTheme = async () => {
              const storedTheme = await getTheme(); // Use getTheme from Locales.tsx
              setThemeTmp(storedTheme);
            };
            loadTheme();
          }, []);

          const toggleTheme  = async () => {
            console.log(theme);
            const newTheme = theme === 'light' ? 'dark' : 'light';
            console.log('provider '+newTheme);
            await persistTheme(newTheme); // Use persistTheme from Locales.tsx
            // setTheme(newTheme); // Update the local state with the new theme
          };

  return (
    <ThemeContext.Provider value={{ theme, toggleTheme }}>
      {children}
    </ThemeContext.Provider>
  );
};

export const useTheme = () => useContext(ThemeContext);
