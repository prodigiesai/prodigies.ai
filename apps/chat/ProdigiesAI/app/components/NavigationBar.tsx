import React from 'react';
import { StyleSheet, View, Text, TouchableOpacity} from "react-native";
import { useNavigation } from 'expo-router';
import { Padding, Border, FontSize, Color, FontFamily } from "@/globals/Styles";
import { AntDesign, Ionicons } from '@expo/vector-icons'; 
import { useRouter } from 'expo-router';
// Define an interface for NavigationBar props
interface NavigationBarProps {
    selected: (buttonName: 'chats' | 'aiAssistants') => void;
    screen: 'chats' | 'aiAssistants';
}

const NavigationBar: React.FC<NavigationBarProps> = ({ selected, screen }) => {

    return (
        <View style={styles.bottomNavigationBar}>
            
            <TouchableOpacity style={styles.navButton} onPress={() => selected('chats')}>
                <Ionicons name="chatbubble-ellipses-outline" size={28} color={Color.greyscale900} />
                <Text style={[styles.navLabel, screen === 'chats' ? styles.navLabelActive : null]}>Chats</Text>
            </TouchableOpacity>

            <TouchableOpacity style={styles.navButton} onPress={() => selected('aiAssistants')}>
                <AntDesign name="appstore-o" size={28} color={Color.greyscale900} />
                {/* <Ionicons name="" size={28} color={Color.greyscale900} /> */}
                <Text style={[styles.navLabel, screen === 'aiAssistants' ? styles.navLabelActive : null]}>AI Assistants</Text>
            </TouchableOpacity>

        </View>
    );
};
const styles = StyleSheet.create({

    bottomNavigationBar: {
        flexDirection: "row",
        backgroundColor: Color.white,
        shadowColor: Color.transparentBlack,
        justifyContent: "space-around",
        paddingTop:Padding.p_8xs,
        
    },
    navButton: {
        alignItems: "center",
        height: 80,
        width:85,
        paddingTop: Padding.p_3xs,
    },

    navLabel: {
        top:2,
        fontFamily: FontFamily.h6Medium,
        color: Color.greyscale500,
        fontSize: FontSize.bodySmall_size,
    },
    navLabelActive: {
        top:2,
        fontFamily: FontFamily.bodyXlarge,
        color: Color.primaryColor,
        fontSize: FontSize.bodySmall_size,
    },
});

export default NavigationBar;