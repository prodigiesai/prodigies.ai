import React,{useState} from 'react';
import { StyleSheet, Switch } from "react-native";
import { Color } from "@/globals/Styles";
import { useTheme } from '@/providers/ThemeProvider';
import { setTheme,getTheme } from '@/services/Locales';

interface BooleanSwitchProps {
  onToggle: () => void; 
  value: boolean; 
}

const useSwitch: React.FC<BooleanSwitchProps> = ({ onToggle, value }) => {

  const [isEnabled, setIsEnabled] = useState(false);
  // Toggle switch by id
  const toggleSwitch = (isEnabled : boolean) => {
    setIsEnabled(isEnabled);
    onToggle;
    console.log(isEnabled);
  };

    return (
        <Switch
            trackColor={{ false: Color.colorWhitesmoke_100, true: Color.primaryColor }}
            thumbColor={isEnabled ? Color.colorWhitesmoke_100 : Color.colorWhitesmoke_100}
            ios_backgroundColor={Color.colorWhitesmoke_100}
            onValueChange={toggleSwitch} 
            value={isEnabled}
            style={styles.switch}
        />
    );
};

const styles = StyleSheet.create({
    switch: {
        width: 50, 
        height: 30,
    },
});

export default useSwitch;
