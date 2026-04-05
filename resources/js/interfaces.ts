export interface NewsGeneratePayload {
    type?: string;
    title: string;
    prompt: string;
    quote?: string;
    highlights: string[];
    acts: number[];
    stage: number;
}
