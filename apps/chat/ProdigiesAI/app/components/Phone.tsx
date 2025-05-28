import * as React from "react";
import { StyleSheet, View, Text, TextInput } from "react-native";
import { useNavigation } from 'expo-router';
import { Border, FontSize, Color } from "@/globals/Styles";
import { useColorScheme } from 'react-native';
import { AntDesign } from '@expo/vector-icons';
interface PhoneProps {
  label?: string;
  value: string;
  onChange: (text: string) => void;
}

const Phone: React.FC<PhoneProps> = ({ label = "", value, onChange }) => {
  const navigation = useNavigation();
  let colorScheme = useColorScheme();
  if (colorScheme === 'dark') {
    // render some dark thing
  } else {
    // render some light thing
  }
  // ******** PHONE ***********//  
  return (

    <View style={styles.container}>
      <Text style={styles.label}>{label}</Text>
        <View style={styles.frame} >
        <TextInput
          // secureTextEntry={true}
          style={styles.inputText}
          placeholder={label}
          placeholderTextColor={Color.greyscale500}
          value={value}
          onChangeText={onChange}
        />
        <AntDesign name="phone" size={24} color={Color.greyscale700} style={styles.icon} />
      </View>
      </View>
  )
};


const styles = StyleSheet.create({

  container: {
    marginTop:2,
    marginBottom:2,
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
    fontSize: FontSize.bodyMedium_size,
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

export default Phone
;
