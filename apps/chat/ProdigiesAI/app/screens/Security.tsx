import React, { useEffect, useState } from 'react';
import { ScrollView, Text, StyleSheet, View, TouchableOpacity } from 'react-native';
import { useNavigation, useRouter } from 'expo-router';
import Button from "@/components/Button";
import Switch from "@/components/useSwitch";
import { FontFamily, FontSize, Color, Padding, Border } from "@/globals/Styles";
import useTranslation from "@/hooks/useTranslation"; 
import { AntDesign, Ionicons } from '@expo/vector-icons';

// Define a type for the switch settings
interface SwitchSetting {
  id: string;
  title: string;
  isEnabled: boolean;
}

const Security: React.FC = () => {
  const navigation = useNavigation();
  const router = useRouter();
  const { translate, isReady } = useTranslation();
  // Define the initial states for each switch
  const initialSwitchSettings: SwitchSetting[] = [
    { id: 'rememberMe', title: 'Remember me', isEnabled: false },
    { id: 'biometricId', title: 'Biometric ID', isEnabled: false },
    { id: 'faceId', title: 'Face ID', isEnabled: false },
    { id: 'smsAuthenticator', title: 'SMS Authenticator', isEnabled: false },
    { id: 'googleAuthenticator', title: 'Google Authenticator', isEnabled: false },
  ];

  const [switchSettings, setSwitchSettings] = useState<SwitchSetting[]>(initialSwitchSettings);

  // Toggle switch by id
  const toggleSwitch = (id: string) => {
    setSwitchSettings(switchSettings.map(setting => 
      setting.id === id ? { ...setting, isEnabled: !setting.isEnabled } : setting
    ));
  };

  useEffect(() => {
    navigation.setOptions({
      headerShown: true,
      title: translate("Security"),
      headerBackVisible: false,
      headerLeft: () => (
        <TouchableOpacity
          style={styles.backButton}
          onPress={() => router.back()}
        >
          <AntDesign name="left" size={24} color={Color.greyscale500} />
        </TouchableOpacity>
      ),
    });
  }, [navigation, isReady, translate]);


  return (
    <>
    <ScrollView style={styles.container}>
      {switchSettings.map((setting) => (
        <View key={setting.id} style={styles.row}>
          {/* <Ionicons name="eye-outline" size={24} color={Color.greyscale700} /> */}
          <Text style={styles.sectionTitle}>{setting.title}</Text>
          <Switch onToggle={() =>toggleSwitch(setting.id)} value={false}/>
        </View>
      ))}

      <TouchableOpacity style={styles.row} onPress={() => { router.push('../screens/Account') }}>
        {/* <AntDesign name="user" size={24} color={Color.greyscale500} /> */}
        <Text style={styles.sectionTitle}>Device Management</Text>
        <Text style={styles.sectionDescription}></Text>
        <AntDesign name="right" size={24} color="black" />
      </TouchableOpacity>
      <View style={styles.buttonPosition}>
          <Button onPress={() => {}} disabled={false} invertColors={true} >
                {translate("Change Password")}
              </Button>                  
        </View>
    </ScrollView>

        </>
  );
};

const styles = StyleSheet.create({
  container: {
    flex: 1,
    paddingHorizontal: Padding.p_base, // Use global padding
    backgroundColor: Color.white, // Use global color
  },
  settingsText: {
    fontSize: FontSize.bodyLarge, // Use global font size
    fontWeight: "bold",
    flex: 1,
  },
  icon: {
    width: 28,
    height: 28,
  },
  row: {
    flexDirection: "row",
    alignItems: "center",
    paddingVertical: 15, // Use global padding

  },
  avatar: {
    width: 80,
    height: 80,
    borderRadius: Border.br_81xl, // Use global border radius
  },
  userInfo: {
    marginLeft: Padding.p_base, // Use global padding
    flex: 1,
  },
  username: {
    fontSize: FontSize.h5_size, // Use global font size
    fontWeight: "bold", // Assume you meant to use the font weight here
    color: Color.greyscale900, // Use global color
  },
  email: {
    fontSize: FontSize.bodyMedium_size, // Use global font size
    color: Color.greyscale700, // Use global color
  },
  upgradeSection: {
    marginTop: Padding.p_5xs, // Use global padding
    borderRadius: Border.br_base, // Use global border radius
    overflow: "hidden",
  },
  groupIcon: {
    width: 83,
    height: 80,
  },
  upgradeInfo: {
    marginLeft: Padding.p_base, // Use global padding
    flex: 1,
  },
  upgradeText: {
    fontSize: FontSize.h5_size, // Use global font size
    fontWeight: "bold",
    color: Color.white, // Use global color
  },
  benefitText: {
    marginTop: Padding.p_5xs, // Use global padding
    fontSize: FontSize.bodySmall_size, // Use global font size
    color: Color.colorWhitesmoke_100, // Use global color
  },
  elementsSettings: {
    flexDirection: 'row',
    alignItems: 'center',
    paddingVertical: Padding.p_base, // Use global padding
    borderBottomColor: Color.primaryMain100, // Use global color
    height: 60,
  },
  sectionIcon: {
    width: 24,
    height: 24,
    marginRight: Padding.p_base, // Use global padding
  },
  sectionTitle: {
    fontSize: FontSize.bodyLarge, // Use global font size
    fontWeight: 'bold',
    flex: 1,
    color: Color.greyscale700
  },
  sectionDescription: {
    marginRight: Padding.p_base, // Use global padding
    fontSize: FontSize.bodyLarge, // Use global font size
  },
  iconlyregularoutlinearrow: {
    width: 24,
    height: 24,
    display: "none"
  },
  title: {
    fontSize: FontSize.bodyMedium_size, // Use global font size
    color: Color.greyscale500, // Use global color
    marginLeft: Padding.p_base, // Use global padding
  },
  darkfalseComponentdividerIcon: {
    maxWidth: "100%",
    overflow: "hidden",
    maxHeight: "100%",
    marginLeft: Padding.p_base, // Use global padding
    flex: 1
  },
  elementssectionDividerDark: {
    width: "100%",
    flexDirection: "row",
    alignItems: "center",
    flex: 1,
    top: 20,
    marginBottom: 30,
  },
  deathSpace: {
    height: 100,
    position: "relative",
    top: 20,
  },
  backButton: {
    marginRight: Padding.p_8xs,
    marginLeft: Padding.p_5xs,
    width: 28,
    height: 28,
  },
  buttonPosition: {
    // width:"100%",
    // flex:1,
    // position: "absolute",
    // alignSelf: "center",
    // bottom: 20,
  },
});


export default Security;
