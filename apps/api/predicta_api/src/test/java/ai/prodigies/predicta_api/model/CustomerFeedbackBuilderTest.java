package ai.prodigies.predicta_api.model;

import org.junit.jupiter.api.Test;
import org.springframework.boot.autoconfigure.orm.jpa.HibernateJpaAutoConfiguration;
import org.springframework.boot.test.autoconfigure.data.mongo.DataMongoTest;
import org.springframework.test.context.ActiveProfiles;

import static org.assertj.core.api.Assertions.assertThat;

@DataMongoTest(excludeAutoConfiguration = {HibernateJpaAutoConfiguration.class})
@ActiveProfiles("test")
public class CustomerFeedbackBuilderTest {

    @Test
    void shouldBuildCustomerFeedbackUsingBuilder() {
        // Arrange
        CustomerFeedback feedback = CustomerFeedback.builder()
                .id("1L")
                .customerName("John Doe")
                .feedback("This is valid feedback")
                .sentiment("Positive")
                .build();

        // Assert
        assertThat(feedback.getId()).isEqualTo("1L");
        assertThat(feedback.getCustomerName()).isEqualTo("John Doe");
        assertThat(feedback.getFeedback()).isEqualTo("This is valid feedback");
        assertThat(feedback.getSentiment()).isEqualTo("Positive");
    }
}