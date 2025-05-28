
import React, { useState, useEffect } from 'react';
import { useFonts } from 'expo-font';
import { Redirect } from 'expo-router';
import { initializeAppSettings } from '@/services/Locales';
import { auth} from '@/services/Firebase';
import { ThemeProvider } from '@/providers/ThemeProvider';


export default function Page() {

  
  initializeAppSettings();

  const [fontsLoaded] = useFonts({
    'Urbanist-Medium': require('./assets/fonts/Urbanist-Medium.ttf'),
    'RoadRage-Regular': require('@/assets/fonts/RoadRage-Regular.ttf'),
    'Urbanist-Regular': require('@/assets/fonts/Urbanist-Regular.ttf'),
    'Urbanist-SemiBold': require('@/assets/fonts/Urbanist-SemiBold.ttf'),
    'Urbanist-Bold': require('@/assets/fonts/Urbanist-Bold.ttf'),
  });

  if (!fontsLoaded) {
    return null; // Or a loading indicator
  }
  // console.log(auth);

  return (
    <ThemeProvider>
      {auth ? <Redirect href="/screens/Index" /> : <Redirect href="/auth/SignIn" />}
    </ThemeProvider>
  );   
  
}