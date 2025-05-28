import * as React from "react";
import { StyleSheet, View, Text, TextInput } from "react-native";
import { useNavigation } from 'expo-router';
import { Border, FontSize, Color } from "@/globals/Styles";
import { Image } from "expo-image";
import { useColorScheme } from 'react-native';
import useTranslation from "@/hooks/useTranslation"; // Ensure this path matches your project structure
import {  Ionicons } from '@expo/vector-icons';
import { TextFieldProps } from '@/interfaces/TextFieldProps';

const Password: React.FC<TextFieldProps> = ({ translationKey, value, onChange, errorTranslationKey }) => {
  const { translate, isReady } = useTranslation();
  const navigation = useNavigation();

  let colorScheme = useColorScheme();

  if (colorScheme === 'dark') {
    // render some dark thing
  } else {
    // render some light thing
  }

  return (
      <View style={styles.container}>
        <Text style={styles.label}>{translate(translationKey)}</Text>
        <View style={styles.frame}>

          <TextInput
            secureTextEntry={true}
            style={styles.inputText}
            placeholder={translate(translationKey)}
            placeholderTextColor={Color.greyscale500}
            value={value}
            onChangeText={onChange}
          />
          <Ionicons name="eye-outline" size={24} color={Color.greyscale700} style={styles.icon}/>
        </View>
      </View>
    )

};

const styles = StyleSheet.create({
  container: {
    marginTop:2,
    marginBottom:10,
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


  icon: {
    top: 16,
    left: 290,
    width: 26,
    height: 26,
    position: "absolute",
  },
 

});

export default Password;
