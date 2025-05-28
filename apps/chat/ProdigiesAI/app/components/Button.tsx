import * as React from "react";
import { StyleSheet, View, TouchableOpacity, Text } from "react-native";
import { Padding, Border, FontSize, Color, FontFamily } from "@/globals/Styles";

// Adjust the props to remove `label` and instead use `children`
interface ButtonProps {
  onPress: () => void;
  disabled: boolean;
  invertColors: boolean;
  children: React.ReactNode; // Accept any React node as children
}

const Button: React.FC<ButtonProps> = ({ onPress, disabled, invertColors = false, children }) => {
  // Determine button and text colors based on `invertColors` prop
  const buttonStyle = [
    styles.button,
    invertColors ? styles.backgroundbuttonColorInverted : styles.backgroundbuttonColor,
    disabled && styles.buttonDisabled, // Add disabled style if button is disabled
  ];

  const textStyle = [
    styles.TextStyle,
    invertColors ? styles.textColorInverted : styles.textColor,
  ];

  return (
    <View style={styles.container}>
      <TouchableOpacity onPress={onPress} style={buttonStyle} disabled={disabled}>
        <Text style={textStyle}>{children}</Text>
      </TouchableOpacity>
    </View>
  );
};


const styles = StyleSheet.create({

  container: {
    marginVertical: 10,
    alignSelf: "center",
    width: "95%",
  },

  button: {
    width: "100%",
    position: "relative",
    height: 60,
    borderRadius: 100,
  },
  buttonDisabled: {
    backgroundColor: "#cccccc", // Different background color when disabled
    opacity: 0.5,
  },

  TextStyle: {
    textAlign: "center",
    letterSpacing: 0,
    lineHeight: 60,
    // fontFamily: FontFamily.bodyXlarge,
    fontSize: FontSize.h5_size,
    fontWeight: "bold",
    flex: 1,

  },
  backgroundbuttonColor: {
    backgroundColor: Color.primaryColor,

  },
  backgroundbuttonColorInverted: {
    backgroundColor: Color.colorWhitesmoke_200,
  },
  textColor: {
    color: Color.white,

  },
  textColorInverted: {
    color: Color.primaryColor,
  }
});

export default Button;
