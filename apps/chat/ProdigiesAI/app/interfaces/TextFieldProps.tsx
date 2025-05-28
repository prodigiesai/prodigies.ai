export interface TextFieldProps {
  translationKey: string; // Use this to fetch translated text for the label and placeholder
  value: string;
  onChange: (text: string) => void;
  errorTranslationKey?: string; // Optional prop for error messages

}