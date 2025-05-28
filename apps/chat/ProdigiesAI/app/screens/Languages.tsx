import React, { useEffect, useState } from 'react';
import { Text, StyleSheet, View, Image, ScrollView, TouchableOpacity } from 'react-native';
import { useNavigation, useRouter } from 'expo-router';
import useTranslation from "@/hooks/useTranslation";
import { AntDesign } from '@expo/vector-icons';
import { FontFamily, FontSize, Color, Padding, Border } from "@/globals/Styles";
import AsyncStorage from '@react-native-async-storage/async-storage'; // Ensure you've installed this package

import Line from '@/components/line';
// Define a type for language settings
type LanguageOption = {
	index: number;
	name: string;
	acronym: string;
	selected: boolean;
};
// Language options array
const languageSuggested: LanguageOption[] = [
	{ index: 0, name: 'English (EN)', acronym: 'en-US', selected: true },
	{ index: 1, name: 'English (UK)', acronym: 'UK', selected: false },
];
// Language options array
const languageOptions: LanguageOption[] = [
	// English (EN-US for the United States, EN-GB for the United Kingdom)
	// Mandarin Chinese (ZH-CN for Simplified Chinese in China, ZH-TW for Traditional Chinese in Taiwan)
	// Spanish (ES-ES for Spain, ES-MX for Mexico, among others for various Spanish-speaking countries)
	// French (FR-FR for France, FR-CA for Canada)
	// German (DE-DE for Germany)
	// Dutch (NL-NL for the Netherlands, NL-BE for Belgium where Dutch is one of the official languages)
	// Hindi (HI-IN for India)
	// Arabic (AR-SA for Saudi Arabia, AR-EG for Egypt, among others for various Arabic-speaking countries)
	// Bengali (BN-BD for Bangladesh, BN-IN for India where Bengali is spoken)
	// Russian (RU-RU for Russia)
	// Japanese (JA-JP for Japan)
	// Korean (KO-KR for South Korea)
	// Indonesian (ID-ID for Indonesia)
	// Turkish (TR-TR for Turkey)
	// Portuguese (PT-PT for Portugal, PT-BR for Brazil)
	// Italian (IT-IT for Italy)
	// Polish (PL-PL for Poland)
	// Ukrainian (UK-UA for Ukraine)
	// Romanian (RO-RO for Romania)
	// Greek (EL-GR for Greece)
	// Hungarian (HU-HU for Hungary)
	// Czech (CS-CZ for Czech Republic; however, the ISO code for Czech is usually "CS", but "CZ" is used in locale codes)
	// Swedish (SV-SE for Sweden)
	// Danish (DA-DK for Denmark)
	// Finnish (FI-FI for Finland)
	// Norwegian (NO-NO for Norway, though there are two written forms, Bokmål and Nynorsk, often just "NO" is used)
	// Slovak (SK-SK for Slovakia)
	// Vietnamese (VI-VN for Vietnam)
	// Thai (TH-TH for Thailand)
	{ index: 2, name: 'Mandarin Chinese', acronym: 'zh-CN', selected: false },
	{ index: 3, name: 'Spanish', acronym: 'es-MX', selected: false },
	{ index: 4, name: 'French', acronym: 'fr-FR', selected: false },
	{ index: 5, name: 'German', acronym: 'de-DE', selected: false },
	{ index: 6, name: 'Dutch', acronym: 'nl-NL', selected: false },
	{ index: 7, name: 'Hindi', acronym: 'hi-IN ', selected: false },
	{ index: 8, name: 'Arabic', acronym: 'ar-SA', selected: false },
	{ index: 9, name: 'Bengali', acronym: 'bn-BD', selected: false },
	{ index: 10, name: 'Russian', acronym: 'ru-RU', selected: false },
	{ index: 11, name: 'Japanese', acronym: 'ja-JP', selected: false },
	{ index: 12, name: 'Korean', acronym: 'ko-KR', selected: false },
	{ index: 13, name: 'Indonesian', acronym: 'id-ID', selected: false },
	{ index: 14, name: 'Turkish', acronym: 'tr-TR', selected: false },
	{ index: 15, name: 'Portuguese', acronym: 'pt-PT', selected: false },
	{ index: 16, name: 'Italian', acronym: 'it-IT', selected: false },
	{ index: 17, name: 'Polish', acronym: 'pl-PL', selected: false },
	{ index: 18, name: 'Ukrainian', acronym: 'uk-UA', selected: false },
	{ index: 19, name: 'Romanian', acronym: 'ro-RO', selected: false },
	{ index: 20, name: 'Greek', acronym: 'el-GR', selected: false },
	{ index: 21, name: 'Hungarian', acronym: 'hu-HU', selected: false },
	{ index: 22, name: 'Czech', acronym: 'cs-CZ', selected: false },
	{ index: 23, name: 'Swedish', acronym: 'sv-SE', selected: false },
	{ index: 24, name: 'Danish', acronym: 'da-DK', selected: false },
	{ index: 25, name: 'Finnish', acronym: 'fi-FI', selected: false },
	{ index: 26, name: 'Norwegian', acronym: 'no-NO', selected: false },
	{ index: 27, name: 'Slovak', acronym: 'sk-SK', selected: false },
	{ index: 28, name: 'Vietnamese', acronym: 'vi-VN', selected: false },
	{ index: 29, name: 'Thai', acronym: 'th-TH', selected: false },
	// Add more languages as needed
];
const Languages = () => {

	const [selectedLanguage, setSelectedLanguage] = useState<string>('EN');
	const navigation = useNavigation();
	const router = useRouter();
	const { translate, isReady } = useTranslation();

	useEffect(() => {
		// Set navigation options
		navigation.setOptions({
		  headerShown: true,
		  title: translate("Languages"),
		  headerBackVisible: false,
		  headerLeft: () => (
			<TouchableOpacity
			  style={styles.backButton}
			  onPress={() => router.back()}
			>
			  <AntDesign name="left" size={24} color="black" />
			</TouchableOpacity>
		  ),
		});
		// Load the selected language from AsyncStorage on component mount
		const loadSelectedLanguage = async () => {
		  const storedLanguage = await AsyncStorage.getItem('selectedLanguage');
		  if (storedLanguage) {
			setSelectedLanguage(storedLanguage);
		  }
		};
		loadSelectedLanguage();
	  }, [navigation, isReady, translate]);


	  const selectLanguage = async (acronym: string) => {
		setSelectedLanguage(acronym);
		await AsyncStorage.setItem('selectedLanguage', acronym);
	  };

		// Helper function to render language option
		const renderLanguageOption = (language: LanguageOption) => (
			<TouchableOpacity
			key={language.acronym}
			style={[styles.elementssettingsListDarkf, styles.languageSpaceBlock]}
			onPress={() => selectLanguage(language.acronym)}
			>
			<Text style={styles.settings}>{language.name}</Text>
			{selectedLanguage === language.acronym && (
				<AntDesign name="check" size={24} color="green" style={{ marginLeft: 'auto' }} />
			)}
			</TouchableOpacity>
		);	  

	return (
		<ScrollView style={styles.container}>

				<View style={styles.group}>
					<Text style={styles.title}>Suggested</Text>
					<Line />
				</View>
				<View>{languageSuggested.map(renderLanguageOption)}</View>
				<View style={styles.group}>
					<Text style={styles.title}>Language</Text>
					<Line />
				</View>
				<View>{languageOptions.map(renderLanguageOption)}</View>



		</ScrollView>
	);
};

const styles = StyleSheet.create({
	container: {
		flex: 1,
		backgroundColor: Color.white,
		paddingTop: Padding.p_mini,
		paddingBottom: Padding.p_5xs,
		paddingHorizontal: Padding.p_lg,
		borderWidth: 1,
	},

	languageSpaceBlock: {
		marginTop: 18,
		alignSelf: "stretch",
		height: 40,
		paddingLeft: 20,
	},

	settings1SpaceBlock: {
		marginLeft: 20,
		display: "none"
	},
	frameLayout: {
		width: 24,
		height: 24
	},
	iconcheckLayout: {
		height: 32,
		width: 32,
		overflow: "hidden",
		marginLeft: 20
	},
	title: {
		color: Color.greyscale500,
		fontFamily: "Urbanist-Bold",
		// fontWeight: "500",
		textAlign: "left",
		// lineHeight: 32,
		fontSize: 14,
		alignSelf: "stretch",


	},
	row: {
		// flexDirection: "row",
		// alignItems: "center",
		// paddingVertical: 15, // Use global padding

	},
	settings: {
		fontSize: FontSize.bodyLarge, // Use global font size
		fontWeight: 'bold',
		flex: 1,
		color: Color.greyscale700,
		paddingLeft: 10,
	},
	settings1: {
		fontFamily: "Urbanist-SemiBold",

	},
	frame: {
		position: "absolute",
		top: 0,
		left: 0,
		backgroundColor: "#fff",
		borderStyle: "solid",
		borderColor: "#eee",
		borderWidth: 2,
		overflow: "hidden",
		borderRadius: 1000,
		width: 24
	},
	enabledfalseThemelightCo: {
		backgroundColor: "#eee",
		width: 44,
		overflow: "hidden",
		height: 24,
		borderRadius: 1000,
		marginLeft: 20,
		display: "none"
	},
	stylenoneThemedefaultSel: {
		marginLeft: 20,
		display: "none"
	},
	iconcheckComponentaddition: {
		overflow: "hidden",
		marginLeft: 20,
		display: "none"
	},
	elementssettingsListDarkf: {
		flexDirection: "row",
		alignItems: "center"
	},
	iconcheckComponentaddition2: {
		display: "none",
		height: 32,
		width: 32
	},
	darkfalseComponentdividerIcon: {
		maxWidth: "100%",
		maxHeight: "100%",
		overflow: "hidden",
		width: "100%"
	},
	language: {
		textAlign: "left",
		lineHeight: 32,
		fontSize: 20,
		color: "#212121",
		fontFamily: "Urbanist-Bold",
		fontWeight: "700"
	},

	backButton: {
		marginRight: Padding.p_8xs,
		marginLeft: Padding.p_5xs,
		width: 28,
		height: 28,
	},

	group: {
		width: "100%",
		flexDirection: "row",
		alignItems: "center",
		flex: 1,
		top: 20,
		marginBottom: 30,
		overflow: 'hidden',
	},

});

export default Languages;