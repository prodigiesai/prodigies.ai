import React from 'react';
import { StyleSheet, View, Text, TextInput } from "react-native";
import { useColorScheme } from 'react-native';
import { Ionicons } from '@expo/vector-icons';
import { FontFamily,Border, FontSize, Color } from "@/globals/Styles";
import useTranslation from "@/hooks/useTranslation"; // Ensure this path matches your project structure
import { TextFieldProps } from '@/interfaces/TextFieldProps';


const TextField: React.FC<TextFieldProps> = ({ translationKey, value, onChange, errorTranslationKey }) => {
  const { translate, isReady } = useTranslation();

  let colorScheme = useColorScheme();

  if (!isReady) {
    return null; // or a placeholder/loading state if you prefer
  }

  return (
    <View style={styles.container}>
      <Text style={styles.label}>{translate(translationKey)}</Text>
      <View style={styles.frame}>
        <TextInput
          style={styles.inputText}
          placeholder={translate(translationKey)}
          placeholderTextColor={Color.greyscale500}
          value={value}
          onChangeText={onChange}
        />
        <Ionicons name="mail-outline" size={24} color={Color.greyscale700} style={styles.icon} />
      </View>
      {errorTranslationKey && (
        <Text style={styles.errorMessage}>{translate(errorTranslationKey)}</Text>
      )}
    </View>
  );
};



const styles = StyleSheet.create({
  container: {
    marginTop:2,
    marginBottom:2,
    // flexDirection: 'column', // Explicitly setting the flex direction
    // justifyContent: 'center', // Adjust this for vertical alignment
    // alignItems: 'center', // Adjust this for horizontal alignment within each item   
    // borderWidth: 1,
    height:110,
  },

  label: {
    bottom: 10,
    fontSize: FontSize.bodyLarge,
    color: Color.greyscale900,
    fontFamily: 'Urbanist-SemiBold',
  },

  frame: {
    alignSelf: "center",
    height: 60,
    width: 345,
    borderRadius: Border.br_31xl,
    borderWidth: 1,
    backgroundColor: Color.colorWhitesmoke_100,
  },

  inputText: {
    left: 20,
    fontSize: FontSize.bodyLarge,
    top: 18,
    width: 250,
    fontFamily: 'Urbanist-SemiBold',
    
  },

  errorMessage:{
    fontSize: FontSize.bodyLarge,
    fontFamily: FontFamily.bodyLarge,
    color: "red",
    textAlign:"right",
    height:"auto",
    right:20,
    top:5,
  },

  icon: {
    top: 16,
    left: 290,
    width: 26,
    height: 26,
    position: "absolute",
  },
  

});

export default TextField;
