import React from 'react';
import AsyncStorage from '@react-native-async-storage/async-storage';
import * as Localization from 'expo-localization';
import { Appearance } from 'react-native';

const defaultLanguage = Localization.getLocales()[0]?.languageTag || 'en';
const defaultTheme = Appearance.getColorScheme() || 'light';

// Initialization function to set up default or custom settings
async function initializeAppSettings() {
  const storedTheme = await AsyncStorage.getItem('theme');
  const storedLanguage = await AsyncStorage.getItem('language');

  // console.log(storedTheme);
  // console.log(storedLanguage);
  // If there are no stored preferences, set them to the system's defaults
  if (!storedTheme) {
    // console.log(defaultTheme);
    await AsyncStorage.setItem('theme', defaultTheme);
  }
  if (!storedLanguage) {
    // console.log(defaultLanguage);
    await AsyncStorage.setItem('language', defaultLanguage);
  }
}

// Function to get the theme, ensuring the type is correct
const getTheme = async (): Promise<'light' | 'dark'> => {
  const theme = await AsyncStorage.getItem('theme');
  return theme === 'dark' ? 'dark' : 'light'; // Ensures the returned type is correct
};

// Function to get the language
const getLanguage = async (): Promise<string> => {
  const language = await AsyncStorage.getItem('language');
  return language || 'en';
};

// Function to set the theme
const setTheme = async (newTheme: 'light' | 'dark') => {
  await AsyncStorage.setItem('theme', newTheme);
};

// Function to set the language
const setLanguage = async (newLanguage: string) => {
  await AsyncStorage.setItem('language', newLanguage);
};

// Export the utility functions
export { getTheme, getLanguage, setTheme, setLanguage, initializeAppSettings };
