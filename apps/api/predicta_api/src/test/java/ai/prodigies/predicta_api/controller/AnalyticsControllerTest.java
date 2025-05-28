package ai.prodigies.predicta_api.controller;

import org.junit.jupiter.api.Test;
import static org.mockito.Mockito.when;
import static org.mockito.Mockito.verify;
import static org.mockito.Mockito.times;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.boot.test.autoconfigure.web.servlet.WebMvcTest;
import org.springframework.boot.test.mock.mockito.MockBean;
import org.springframework.test.web.servlet.MockMvc;
import org.springframework.http.MediaType;
import java.util.List;
import java.nio.charset.StandardCharsets;
import static org.springframework.test.web.servlet.request.MockMvcRequestBuilders.get;
import static org.springframework.test.web.servlet.result.MockMvcResultMatchers.*;
import ai.prodigies.predicta_api.service.CustomerFeedbackService;
import ai.prodigies.predicta_api.model.CustomerFeedback;
import io.jsonwebtoken.Jwts;
import io.jsonwebtoken.SignatureAlgorithm;
import io.jsonwebtoken.security.Keys;
import javax.crypto.SecretKey;
import org.springframework.boot.test.autoconfigure.web.servlet.AutoConfigureMockMvc;

@WebMvcTest(AnalyticsController.class)
@AutoConfigureMockMvc(addFilters = false) // Disable security filters
public class AnalyticsControllerTest {

    @Autowired
    private MockMvc mockMvc;

    @MockBean
    private CustomerFeedbackService customerFeedbackService;

    private String generateMockJwt() {
        SecretKey key = Keys.secretKeyFor(SignatureAlgorithm.HS256); // Generates a secure key
        return Jwts.builder()
                .setSubject("testuser")
                .signWith(key, SignatureAlgorithm.HS256)
                .compact();
    }

    @Test
    void shouldReturnFeedbackByCustomerName() throws Exception {
        // Arrange
        String customerName = "John Doe";
        List<CustomerFeedback> feedbackList = List.of(new CustomerFeedback("1L", "John Doe", "Great service!", "Positive"));

        when(customerFeedbackService.getFeedbackByCustomerName(customerName)).thenReturn(feedbackList);

        String mockJwt = generateMockJwt();

        // Act & Assert
        mockMvc.perform(get("/api/analytics/feedback/customer/{customerName}", customerName)
                        .header("Authorization", "Bearer " + mockJwt)
                        .contentType(MediaType.APPLICATION_JSON))
                .andExpect(status().isOk())
                .andExpect(jsonPath("$[0].customerName").value("John Doe"))
                .andExpect(jsonPath("$[0].feedback").value("Great service!"));

        verify(customerFeedbackService, times(1)).getFeedbackByCustomerName(customerName);
    }

    @Test
    void shouldReturnHealthStatus() throws Exception {
        mockMvc.perform(get("/api/analytics/health"))
                .andExpect(status().isOk())
                .andExpect(content().string("API is running!"));
    }
}