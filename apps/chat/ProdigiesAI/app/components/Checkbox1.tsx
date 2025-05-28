
import React from 'react';
import { StyleSheet, View } from "react-native";
import { Color } from "@/globals/Styles";
import Checkbox from 'expo-checkbox';

interface CheckboxProps {
    setChecked: (isChecked: boolean) => void; // Accept a boolean parameter
    isChecked: boolean;
  };
  
  
  const Checkbox1: React.FC<CheckboxProps> = ({  setChecked,  isChecked }) => {
    
    return (
        <Checkbox
          style={styles.checkbox}
          value={isChecked}
          onValueChange={(newValue) => setChecked(newValue)}
          color={isChecked ? '#4630EB' : undefined}
        />
        );
};

const styles = StyleSheet.create({
    checkbox: {
        margin: 8,
      },
    
});

export default Checkbox1;
