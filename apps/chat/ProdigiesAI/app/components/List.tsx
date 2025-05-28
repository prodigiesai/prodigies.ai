import * as React from "react";
import { StyleSheet, View, Text  } from "react-native";
import { useNavigation } from 'expo-router';
import { Border, FontSize, Color, FontFamily } from "@/globals/Styles";
import { Dropdown } from 'react-native-element-dropdown';
import { useColorScheme } from 'react-native';

interface ListProps {
  label?: string;
  value: string;
  onChange: (text: string) => void;
}

const List: React.FC<ListProps> = ({ label = "", value, onChange }) => {
  const navigation = useNavigation();
  let colorScheme = useColorScheme();

  if (colorScheme === 'dark') {
    // render some dark thing
  } else {
    // render some light thing
  }

  const data = [
    { label: 'Male', value: '1' },
    { label: 'Female', value: '2' },
  ];

  // ******** LIST ***********//
  const [valueSelected, setValue] = React.useState<string | null>(null);
  const [isFocus, setIsFocus] = React.useState(false);

  const renderLabel = () => {
    if (value || isFocus) {
      return (
        <Text style={[styles.label, isFocus && { color: 'blue' }]}>
          Dropdown label
        </Text>
      );
    }
    return null;
  };
  return (

    <View style={styles.container}>
      <Text style={styles.label}>{label}</Text>
      <Dropdown
        style={[styles.dropdown, isFocus && { borderColor: 'blue' }]}
        placeholderStyle={styles.placeholderStyle}
        selectedTextStyle={styles.selectedTextStyle}
        inputSearchStyle={styles.inputSearchStyle}
        iconStyle={styles.iconStyle}
        data={data}
        //search
        maxHeight={300}
        labelField="label"
        valueField="value"
        placeholder={!isFocus ? 'Gender' : '...'}
        searchPlaceholder="Search..."
        value={valueSelected}
        onFocus={() => setIsFocus(true)}
        onBlur={() => setIsFocus(false)}
        onChange={item => {
          setValue(item.value);
          setIsFocus(false);
        }}
      />
    </View>
  )
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
    fontFamily: FontFamily.bodyLargeSemibold,
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
    fontFamily: FontFamily.bodyLargeSemibold,
    
  },
 
  icon: {
    top: 16,
    left: 290,
    width: 26,
    height: 26,
    position: "absolute",
  },

  dropdown: {
    borderRadius: Border.br_31xl,
    borderWidth: 1,
    width: 350,
    height: 60,
    paddingHorizontal: 8,
    backgroundColor: Color.colorWhitesmoke_100,
  },

  placeholderStyle: {
    backgroundColor: 'transparent',
    fontSize: FontSize.bodyMedium_size,
    fontFamily: FontFamily.bodyLargeSemibold,
    fontWeight: "500",
    color: Color.greyscale500,
    left: 15,

  },
  selectedTextStyle: {
    backgroundColor: 'transparent',
    fontSize: FontSize.bodyMedium_size,
    fontFamily: FontFamily.bodyLargeSemibold,
    fontWeight: "500",
    color: Color.greyscale900,
    left: 15,
  },
  iconStyle: {
    right: 20,
    width: 28,
    height: 28,
  },
  inputSearchStyle: {
    height: 40,
    fontSize: 16,
  },
});

export default List;
