import * as React from "react";
import { StyleSheet, View, Text, Pressable, TextInput, Image,Platform } from "react-native";
import DateTimePicker, { DateTimePickerEvent } from '@react-native-community/datetimepicker';
import { useColorScheme } from 'react-native';
import { FontFamily, FontSize, Color, Padding, Border } from "../globals/Styles";
import { AntDesign } from '@expo/vector-icons';
interface DatePickerProps {
  label?: string;
  value: Date;
  onChange: (date: Date) => void;
}
const DatePicker: React.FC<DatePickerProps> = ({ label = "", value, onChange }) => {
  const colorScheme = useColorScheme();
  const [date, setDate] = React.useState(value || new Date());
  const [show, setShow] = React.useState(false);


  const handleChange = (event: DateTimePickerEvent, selectedDate?: Date) => {
    const currentDate = selectedDate || date;
    setShow(Platform.OS === 'ios');
    setDate(currentDate);
  };

  const onConfirm = () => {
    setShow(false);
    onChange(date); // Pass the current date state back to the parent component
  };

  const onCancel = () => {
    setShow(false);
  };

  const showDatePicker = () => {
    setShow(true);
  };

  return (
    <View style={styles.container}>
      <Text style={styles.label}>{label}</Text>
      {!show && (
        <View style={styles.frame}>

          <Pressable style={styles.showDatePicker} onPress={showDatePicker}>
       
            <Text style={styles.inputText}>
              {date.toLocaleDateString()} {/* Display date in a readable format */}
            </Text>
              <AntDesign name="calendar" size={24} color={Color.greyscale700} />
          </Pressable>
        </View>
      )}
      {show && (
        <View>
        <DateTimePicker
          testID="dateTimePicker"
          value={date}
          mode="date"
          display={Platform.OS === 'ios' ? 'spinner' : 'default'}
          maximumDate={new Date()}
          onChange={handleChange}
        />
          { show && Platform.OS === "ios" && (
          <View style={styles.buttons}>
            <Pressable style={styles.buttonCancel} onPress={onCancel}>
              <Text style={styles.buttonText}>Cancel</Text>
            </Pressable>
            <Pressable style={styles.buttonConfirm} onPress={onConfirm}>
              <Text style={styles.buttonText}>Confirm</Text>
            </Pressable>
          </View>
          )}
        </View>
      )}
    </View>
  );
};

const styles = StyleSheet.create({
  container: {
    marginTop: 2,
    marginBottom: 2,
  },
  label: {
    fontSize: FontSize.bodyLarge,
    color: Color.greyscale900,
    fontFamily: FontFamily.bodyLargeSemibold,
    marginBottom: 10,
  },
  frame: {
    alignSelf: "center",
    height: 60,
    width: 345,
    borderRadius: Border.br_31xl,
    borderWidth: 1,
    backgroundColor: Color.colorWhitesmoke_100,
    flexDirection: 'row',
    justifyContent: 'space-between',
    alignItems: 'center',
    paddingHorizontal: 20,
  },
  inputText: {
    fontSize: FontSize.bodyLarge,
    fontFamily: FontFamily.bodyLargeSemibold,
    width:265,
  },
  icon: {
    width: 26,
    height: 26,
  },
  showDatePicker: {
    // Additional styles if necessary
    flexDirection: 'row',
    // borderWidth:1,

  },

  buttons:{
    flex:1,
    flexDirection: 'row',
    alignItems:"center",
    alignSelf:"center",
  },

  buttonCancel: {
    // backgroundColor: "green",
    borderColor: Color.greyscale500,
    margin:5,
    borderRadius: 20,
    borderWidth: 1,
    alignItems: "center",
    width: 100,
  },
  buttonConfirm: {
    margin:5,
    borderColor: Color.greyscale500,
    borderRadius: 20,
    borderWidth: 1,
    alignItems: "center",
    width: 100,  
  },

  buttonText: {
    lineHeight: 40,
    flex: 1,
    color: Color.primaryColor,

  }
});

export default DatePicker;
