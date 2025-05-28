import React, { useEffect, useState } from 'react';
import { ScrollView, Text, StyleSheet, Image, View, TouchableOpacity } from 'react-native';
import { LinearGradient } from 'expo-linear-gradient';
import { useNavigation, useRouter } from 'expo-router';
import { AntDesign, Ionicons } from '@expo/vector-icons';
import { FontFamily, FontSize, Color, Padding, Border, light_styles, dark_styles } from "@/globals/Styles";
import useTranslation from "@/hooks/useTranslation"; 
import Switch from "@/components/useSwitch";
import Line from '@/components/line';
import { signOutUser } from '@/services/Firebase'; 
import { useTheme } from '@/providers/ThemeProvider'; 


const Menu = () => {
  const navigation = useNavigation();
  const router = useRouter(); 
  const { translate, isReady } = useTranslation();
  const { theme, toggleTheme } = useTheme(); 

  const themeStyles = theme === 'light' ? light_styles : dark_styles;
  const isDarkMode = theme === 'dark';

  const handleSignOut = async () => {
    await signOutUser();
    router.push(require('@/auth/Welcome'));
   
  };

  useEffect(() => {
    navigation.setOptions({
      headerShown: true,
      title: translate("Account"),
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
  },[navigation, isReady, translate]);


  return (
    <ScrollView style={[styles.container,themeStyles.themeContainer]}>

      <TouchableOpacity style={styles.row} onPress={() => { }}>     
        <View style={styles.userInfo}>
          <Text style={styles.username}>Prodigies AI</Text>
          <Text style={styles.email}>support@prodigies.ai</Text>
        </View>
      </TouchableOpacity>
      <LinearGradient
        style={styles.upgradeSection}
        colors={["#7e92f8", "#6972f0"]}
      >
        <TouchableOpacity style={styles.row} onPress={() => { }}>
        <Image style={styles.upgradeInfo} source={require("@/assets/upgrade.png")} />
        </TouchableOpacity>
      </LinearGradient>

      <TouchableOpacity style={styles.row} onPress={() => { router.push('../screens/Account') }}>
        <AntDesign name="user" size={24} color={Color.greyscale700} />
        <Text style={styles.sectionTitle}>Personal Info</Text>
        <Text style={styles.sectionDescription}></Text>
        <AntDesign name="right" size={24} color="black" />
      </TouchableOpacity>
      <TouchableOpacity style={styles.row} onPress={() => { router.push('../screens/Security') }}>
        <AntDesign name="Safety" size={24} color={Color.greyscale700} />
        <Text style={styles.sectionTitle}>Security</Text>
        <Text style={styles.sectionDescription}></Text>
        <AntDesign name="right" size={24} color="black" />
      </TouchableOpacity>
      <TouchableOpacity style={styles.row} onPress={() => { router.push('../screens/Security') }}>
        <AntDesign name="database" size={24} color={Color.greyscale700} />
        <Text style={styles.sectionTitle}>Data Controls</Text>
        <Text style={styles.sectionDescription}></Text>
        <AntDesign name="right" size={24} color="black" />
      </TouchableOpacity>
      <TouchableOpacity style={styles.row} onPress={() => { router.push('../screens/Languages') }}>
        <Ionicons name="language" size={24} color={Color.greyscale700} />
        <Text style={styles.sectionTitle}>Language</Text>
        <Text style={styles.sectionDescription}>English (US)</Text>
        <AntDesign name="right" size={24} color="black" />
      </TouchableOpacity>

      <View style={styles.row}>
        <Ionicons name="eye-outline" size={24} color={Color.greyscale700} />
        <Text style={styles.sectionTitle}>Dark Mode</Text>
        <View style={styles.SwitchPosition}>
          <Switch onToggle={toggleTheme} value={isDarkMode} />
        </View>

      </View>
      <View style={styles.elementssectionDividerDark}>
        <Text style={styles.title}>About</Text>
        <Line />
      </View>
      <TouchableOpacity style={styles.row} onPress={() => { router.push('../screens/Help') }}>
        <Ionicons name="help" size={24} color={Color.greyscale700} />
        <Text style={styles.sectionTitle}>Help Center</Text>
        <AntDesign name="right" size={24} color="black" />
      </TouchableOpacity>
      <TouchableOpacity style={styles.row} onPress={() => { }}>
        <Ionicons name="ios-lock-closed-outline" size={24} color={Color.greyscale700} />
        <Text style={styles.sectionTitle}>Privacy Policy</Text>
        <AntDesign name="right" size={24} color="black" />
      </TouchableOpacity>
      <TouchableOpacity style={styles.row} onPress={() => { }}>
       
        <Ionicons name="information-circle-outline" size={24} color={Color.greyscale700} />
        <Text style={styles.sectionTitle}>About ProdigiesAI</Text>
        <AntDesign name="right" size={24} color="black" />
      </TouchableOpacity>
      <TouchableOpacity style={styles.row} onPress={() => { router.push('../screens/Languages') }}>
        <Ionicons name="language" size={24} color={Color.greyscale700} />
        <Text style={styles.sectionTitle}>Version</Text>
        <Text style={styles.sectionDescription}>1.0.0</Text>

      </TouchableOpacity>
      <TouchableOpacity style={styles.row} onPress={handleSignOut}>
        <Ionicons name="log-out-outline" size={24} color={Color.red} />
        <Text style={[styles.sectionTitle, styles.signOut]}>Sign Out</Text>
      </TouchableOpacity>
      <View style={styles.deathSpace}></View>
    </ScrollView>
  );
};

const styles = StyleSheet.create({
  container: {
    flex: 1,
    paddingHorizontal: Padding.p_base, 

  },
  settingsText: {
    fontSize: FontSize.bodyLarge, 
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
    paddingVertical: 15, 

  },
  avatar: {
    width: 80,
    height: 80,
    borderRadius: Border.br_81xl, 
  },
  userInfo: {
    marginLeft: Padding.p_base, 
    flex: 1,
  },
  username: {
    fontSize: FontSize.h5_size, 
    fontWeight: "bold", 
    color: Color.greyscale900, 
  },
  email: {
    fontSize: FontSize.bodyMedium_size,
    color: Color.greyscale700, 
  },
  upgradeSection: {
    marginTop: Padding.p_5xs, 
    borderRadius: Border.br_base, 
    overflow: "hidden",
  },
  groupIcon: {
    width: 83,
    height: 80,
  },
  upgradeInfo: {
    marginLeft: Padding.p_base, 
    flex: 1,
    height: 70,
  },
  upgradeText: {
    fontSize: FontSize.h5_size,
    fontWeight: "bold",
    color: Color.white,
  },
  benefitText: {
    marginTop: Padding.p_5xs, 
    fontSize: FontSize.bodySmall_size, 
    color: Color.colorWhitesmoke_100,
  },
  elementsSettings: {
    flexDirection: 'row',
    alignItems: 'center',
    paddingVertical: Padding.p_base, 
    borderBottomColor: Color.primaryMain100, 
    height: 60,
  },
  sectionIcon: {
    width: 24,
    height: 24,
    marginRight: Padding.p_base, 
  },
  sectionTitle: {
    fontSize: FontSize.bodyLarge, 
    fontWeight: 'bold',
    flex: 1,
    color: Color.greyscale700,
    paddingLeft: 10,
  },
  signOut: {
    color: Color.red,
  },

  sectionDescription: {
    marginRight: Padding.p_base, 
    fontSize: FontSize.bodyLarge, 
  },
  iconlyregularoutlinearrow: {
    width: 24,
    height: 24,
    display: "none"
  },
  title: {
    fontSize: FontSize.bodyMedium_size, 
    color: Color.greyscale500, 
    marginLeft: Padding.p_base, 
  },
  darkfalseComponentdividerIcon: {
    maxWidth: "100%",
    overflow: "hidden",
    maxHeight: "100%",
    marginLeft: Padding.p_base, 
    flex: 1
  },
  elementssectionDividerDark: {
    width: "100%",
    flexDirection: "row",
    alignItems: "center",
    flex: 1,
    top: 20,
    marginBottom: 30,
    overflow:'hidden'
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

  rectangle1: {
    backgroundColor: "#CCC",
    height: 1,
    marginHorizontal: 24,
    // alignSelf: "stretch",
    // borderWidth: 1,
    width: '100%',
    flex: 1,
  },
  autoLayoutVertical1: {
    justifyContent: "flex-end",
    paddingHorizontal: 0,
    paddingVertical: 1,
    alignItems: "center"
  },

  rectangleSpaceBlock: {
    marginTop: 12,
    alignSelf: "stretch"
  },

  SwitchPosition: {
    position: 'absolute',
    right: 0,

  }

});


export default Menu;
