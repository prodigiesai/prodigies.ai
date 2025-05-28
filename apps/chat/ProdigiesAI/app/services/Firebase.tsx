import { useState, useEffect } from 'react';
import { initializeApp, getApp } from "firebase/app";
import { initializeAuth, getAuth, getReactNativePersistence,createUserWithEmailAndPassword,signOut,onAuthStateChanged,User } from 'firebase/auth';
import ReactNativeAsyncStorage from '@react-native-async-storage/async-storage';

// Optionally import the services that you want to use
// Import the functions you need from the SDKs you need
// import { getAuth } from "firebase/auth";
// TODO: Add SDKs for Firebase products that you want to use
// https://firebase.google.com/docs/web/setup#available-libraries

// import {...} from "firebase/auth";
// import {...} from "firebase/database";
// import {...} from "firebase/firestore";
// import {...} from "firebase/functions";
// import {...} from "firebase/storage";

// Initialize Firebase
const firebaseConfig = {
  databaseURL: 'https://prodigies-7d24e.firebaseio.com',
  measurementId: 'G-measurement-id',
  apiKey: "AIzaSyAQAcoZyjfuYV6TIomz49dv1V9QrMhIxi4",
  authDomain: "prodigies-7d24e.firebaseapp.com",
  projectId: "prodigies-7d24e",
  storageBucket: "prodigies-7d24e.appspot.com",
  messagingSenderId: "125473855248",
  appId: "1:125473855248:web:59a932015be963d8323795"
};

// For more information on how to access Firebase in your project,
// see the Firebase documentation: https://firebase.google.com/docs/web/setup#access-firebase

// initialize Firebase App
const app = initializeApp(firebaseConfig);
// initialize Firebase Auth for that app immediately
initializeAuth(app, {
  persistence: getReactNativePersistence(ReactNativeAsyncStorage)
});
const auth = getAuth(app);

const signOutUser = async () => {
  try {
    await signOut(auth);
    console.log('User signed out successfully');
  } catch (error) {
    console.error('Error signing out: ', error);
  }
};

export { app, auth, getApp, getAuth, createUserWithEmailAndPassword ,signOutUser,initializeAuth,onAuthStateChanged,User};


